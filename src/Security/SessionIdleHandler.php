<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class SessionIdleHandler
{
    protected $session;
    protected $securityToken;
    protected $router;
    protected $maxIdleTime;
    protected $flashBag;

    public function __construct($maxIdleTime, SessionInterface $session, TokenStorageInterface $securityToken, RouterInterface $router, FlashBagInterface $flashBag)
    {
        $this->session = $session;
        $this->securityToken = $securityToken;
        $this->router = $router;
        $this->maxIdleTime = $maxIdleTime;
        $this->flashBag = $flashBag;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        if (HttpKernelInterface::MASTER_REQUEST != $event->getRequestType())
            return;

        if ($this->maxIdleTime > 0)
        {
            $this->session->start();
            $lapse = time() - $this->session->getMetadataBag()->getLastUsed();

            if ($lapse > $this->maxIdleTime)
            {
                $this->securityToken->setToken(null);
                $this->flashBag->add('info', 'Vous avez été déconnecté pour inactivité');

                $event->setResponse(new RedirectResponse($this->router->generate('logout')));
            }
        }
    }
}