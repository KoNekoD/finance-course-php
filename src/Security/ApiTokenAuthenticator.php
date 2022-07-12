<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class ApiTokenAuthenticator
{
    public function supports(Request $request): bool
    {
        // look for header "Authorization: Bearer <token>"
        return $request->headers->has('Authorization')
            && 0 === strpos($request->headers->get('Authorization'), 'Bearer ');
    }

    public function getCredentials(Request $request)
    {
        $authorizationHeader = $request->headers->get('Authorization');
        // skip beyond "Bearer "
        return substr($authorizationHeader, 7);
    }
}