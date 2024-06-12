<?php

namespace ApiBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class AuthController
{
    public function __construct()
    {
        global $urlgenerator, $_V, $_SITE;
        
        $this->site = $_SITE;
        $this->user = $_V;
        $this->url = $urlgenerator;
        
        $this->um = new \UserManager();
        $this->sm = new \SessionManager();
    }
    
    /**
     * POST /api/auth
     */
    public function authAction(Request $request)
    {
        $response = new JsonResponse();
        if ($request->getMethod() == "POST") {
            
            $login = $request->request->get('login', false);
            $password = $request->request->get('password', false);
            
            if ($login && $password) {
              
                $user = $this->um->authenticate($login, $password);
                
                // If credentials are correct, create a new session and return token
                if ($user) {
                    
                    // If e-mail has not been validated
                    if ($user->has('email_key')) {
                        $response = new JsonResponse(['error' => 'E-mail address has not been validated']);
                        $response->setStatusCode(403);
                    } else {
                        $session = $this->sm->create([
                            'user_id' => $user->get('id')
                        ]);
                        $response = new JsonResponse(['token' => $session->get('token')]);
                    }
                } else {
                    $response = new JsonResponse(['error' => 'Bad credentials']);
                    $response->setStatusCode(403);
                }
            
            // Else if missing credentials
            } else {
                $response = new JsonResponse(['error' => 'Missing credentials']);
                $response->setStatusCode(412);
            }
        
        // Else if not POST
        } else {
            $response = new JsonResponse(['error' => 'Bad request']);
            $response->setStatusCode(400);
        }
        return $response;
    }
    
    public function meAction(Request $request)    
    {
        $user = $this->getUser($request);
        
        if ($user) {
            $response = new JsonResponse(['user' => [
                'id' => $user->get('id'),
                'email' => $user->get('email'),
                'username' => $user->get('screen_name')
            ]]);
        } else {
            $response = new JsonResponse(['error' => 'Not authorized']);
            $response->setStatusCode(403);
        }
        
        return $response;
    }
    
    private function getUser($request)
    {
        
        // If a token has been sent in HTTP headers
        $sent_token = $request->headers->get('AuthToken');
        if ($sent_token) {
            
            // And if there is a session with this header
            $session = $this->sm->get(['session_token' => $sent_token]);
            if ($session) {
                
                // And if session is still valid
                if ($session->isValid()) {
                    
                    // And if User's session exists
                    $user = $this->um->getById($session->get('user_id'));
                    if ($user) {
                        
                        // Then, return User
                        return $user;
                    }
                }
            }
        } 
        
        return false;
    }
}
