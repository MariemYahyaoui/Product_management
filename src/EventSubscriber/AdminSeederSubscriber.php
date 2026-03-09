<?php

namespace App\EventSubscriber;

use App\Entity\Category;
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

        $this->seedAdmin();
        $this->seedCategories();
    }

    private function seedAdmin(): void
    {
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

    private function seedCategories(): void
    {
        $repo = $this->em->getRepository(Category::class);

        // Only seed if no categories exist at all
        if ($repo->count([]) > 0) {
            return;
        }

        $categories = [
            ['Fruits & Vegetables', 'Fresh organic fruits and vegetables'],
            ['Dairy & Eggs', 'Milk, cheese, yogurt, and farm-fresh eggs'],
            ['Meat & Poultry', 'Premium quality meat, chicken, and poultry products'],
            ['Seafood', 'Fresh and frozen fish, shrimp, and shellfish'],
            ['Bakery', 'Freshly baked bread, pastries, and cakes'],
            ['Beverages', 'Juices, soft drinks, water, and energy drinks'],
            ['Snacks & Confectionery', 'Chips, chocolates, candies, and cookies'],
            ['Frozen Foods', 'Frozen meals, ice cream, and frozen vegetables'],
            ['Pantry Staples', 'Rice, pasta, flour, oil, and canned goods'],
            ['Household & Cleaning', 'Cleaning supplies, detergents, and household essentials'],
        ];

        foreach ($categories as [$name, $description]) {
            $category = new Category();
            $category->setCategoryName($name);
            $category->setDescription($description);
            $this->em->persist($category);
        }

        $this->em->flush();
    }
}
