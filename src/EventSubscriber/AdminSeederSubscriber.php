<?php

namespace App\EventSubscriber;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AdminSeederSubscriber implements EventSubscriberInterface
{
    private static bool $checked = false;

    public function __construct(
        private EntityManagerInterface $em,
        private UserPasswordHasherInterface $passwordHasher,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => ['onKernelRequest', 255],
        ];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest() || self::$checked) {
            return;
        }

        self::$checked = true;

        $existing = $this->em->getRepository(User::class)->findOneBy(['email' => 'admin@admin.com']);
        if ($existing) {
            return;
        }

        $admin = new User();
        $admin->setFirstName('Admin');
        $admin->setLastName('Admin');
        $admin->setEmail('admin@admin.com');
        $admin->setRole('ADMIN');
        $admin->setPassword(
            $this->passwordHasher->hashPassword($admin, '123')
        );

        $this->em->persist($admin);
        $this->em->flush();
    }
}
