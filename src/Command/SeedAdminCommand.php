<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:seed-admin',
    description: 'Creates the default Admin user if it does not exist.',
)]
class SeedAdminCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $em,
        private UserPasswordHasherInterface $passwordHasher,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $existing = $this->em->getRepository(User::class)->findOneBy(['email' => 'admin@admin.com']);

        if ($existing) {
            $io->info('Admin user already exists. Skipping.');
            return Command::SUCCESS;
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

        $io->success('Admin user created (email: admin@admin.com, password: 123).');

        return Command::SUCCESS;
    }
}
