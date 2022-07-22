<?php

namespace ApiBundle\Controller;

use Biblys\Service\CurrentUser;
use Biblys\Service\Log;
use Framework\Controller;
use Framework\Exception\AuthException;
use Model\Session;
use Model\UserQuery;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class AuthController extends Controller
{

    /**
     * POST /api/auth
     * @throws AuthException
     * @throws PropelException
     */
    public function authAction(Request $request): JsonResponse
    {
        $login = $request->request->get('login', false);
        $password = $request->request->get('password', false);

        if (!$login || !$password) {
            throw new BadRequestHttpException("Credentials are missing");
        }

        $userByEmail = UserQuery::create()->findOneByEmail($login);
        $userByUsername = UserQuery::create()->findOneByUsername($login);
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
            throw new AuthException("Email address has not been validated");
        }

        $session = Session::buildForUser($user);
        $session->save();

        return new JsonResponse(['token' => $session->getToken()]);
    }

    /**
     * @throws AuthException
     */
    public function meAction(CurrentUser $currentUserService): JsonResponse
    {
        $user = $currentUserService->getUser();

        return new JsonResponse(['user' => [
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'username' => $user->getUsername(),
        ]]);
    }
}
