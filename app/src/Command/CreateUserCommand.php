<?php

namespace App\Command;

use App\Security\UserManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateUserCommand extends Command
{
    protected static $defaultName = 'app:create-user';

    /**
     * @var UserManager
     */
    protected $userManager;

    public function __construct(UserManager $userManager)
    {
        $this->userManager = $userManager;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Creates a new user.')
            ->setHelp('This command allows you to create a user...')
            ->addArgument('username', InputArgument::REQUIRED, 'The username of the user.')
            ->addArgument('password', InputArgument::REQUIRED, 'User password')
            ->addArgument('email', InputArgument::REQUIRED, 'The email of the user.')
            ->addArgument('roles', InputArgument::REQUIRED, 'Roles separated by comma')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
            'User Creator',
            '============',
            '',
        ]);

        // retrieve the argument value using getArgument()
        $output->writeln('Username: '.$input->getArgument('username'));
        $output->writeln('Email: '.$input->getArgument('email'));

        $this->userManager->createUser(
            $input->getArgument('username'),
            $input->getArgument('email'),
            $input->getArgument('password'),
            explode(',', $input->getArgument('roles'))
        );

        $output->writeln('User successfully generated!');
    }
}