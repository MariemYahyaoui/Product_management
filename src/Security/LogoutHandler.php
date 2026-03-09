<?php

namespace App\Security;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Security\Http\Event\LogoutEvent;

#[AsEventListener(event: LogoutEvent::class)]
class LogoutHandler
{
    public function __invoke(LogoutEvent $event): void
    {
        $response = $event->getResponse();
        if ($response) {
            $response->headers->clearCookie('AUTH_TOKEN', '/');
        }
    }
}
