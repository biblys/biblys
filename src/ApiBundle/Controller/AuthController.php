<?php

namespace ApiBundle\Controller;

use Biblys\Service\CurrentUser;
use Biblys\Service\Log;
use DateTime;
use Framework\Controller;
use Model\AxysAccountQuery;
use Model\Session;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class AuthController extends Controller
{

    /**
     * POST /api/auth
     * @throws PropelException
     */
    public function authAction(Request $request): JsonResponse
    {
        $login = $request->request->get('login', false);
        $password = $request->request->get('password', false);

        if (!$login || !$password) {
            throw new BadRequestHttpException("Credentials are missing");
        }

        $userByEmail = AxysAccountQuery::create()->findOneByEmail($login);
        $userByUsername = AxysAccountQuery::create()->findOneByUsername($login);
        $user = $userByEmail ?: $userByUsername;

        if (!$user) {
            Log::security("ERROR", "User unknown for login $login");
            throw new UnauthorizedHttpException("", "Bad credentials");
        }

        if (!password_verify($password, $user->getPassword())) {
            Log::security("ERROR", "Wrong password for login $login");
            throw new UnauthorizedHttpException("", "Bad credentials");
        }

        if ($user->getEmailKey() !== null) {
            throw new AccessDeniedHttpException("Email address has not been validated");
        }

        $session = new Session();
        $session->setAxysAccount($axysAccount);
        $session->setToken(Session::generateToken());
        $session->setExpiresAt(new DateTime('tomorrow'));
        $session->save();

        return new JsonResponse(['token' => $session->getToken()]);
    }

    public function meAction(CurrentUser $currentUserService): JsonResponse
    {
        $user = $currentUserService->getAxysAccount();

        return new JsonResponse(['user' => [
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'username' => $user->getUsername(),
        ]]);
    }
}
