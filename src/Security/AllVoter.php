<?php

namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class SecurityControl
{

    protected $auth;

    public function __construct(AuthorizationChecker $auth)
    {
        $this->auth = $auth;
    }

    public function checkPermissionUser(GetResponseEvent $event)
    {
        $routeName = $event->getRequest()->attributes->get('_route');
        //Se exceptuan las rutas que tengan que ver con fos (Friends of symfony), porque esto tiene que ver con el login, registro, recuperación de contraseñas...
        //También lo que tiene que ver con el profiler (Barra de desarrollo) y cosas del sistema como twig y wdt. También las que están permitidas por
        //los access control
        if ($routeName != null
            && stripos($routeName, '_anonymous') === false
            && stripos($routeName, '_twig_') === false
            && stripos($routeName, '_profiler') === false
            && stripos($routeName, 'roulette_') === false
            && stripos($routeName, '_wdt') === false) {
            if (!$this->auth->isGranted($routeName)) {
                $exception = new AccessDeniedException('Access denied');
                $exception->setAttributes($routeName);
                throw $exception;
            }
        }
    }
}
