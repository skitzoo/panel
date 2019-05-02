<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Command\LockableTrait;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SetRoleUserCommand extends Command
{
    use LockableTrait;

    private $em;
    private $validator;

    public function __construct(?string $name = null, EntityManagerInterface $em, ValidatorInterface $validator)
    {
        $this->em = $em;
        $this->validator = $validator;

        parent::__construct($name);
    }

    protected function configure()
    {
        $this
            ->setName('app:set-role-user')
            ->setDescription('Définir un rôle sur un utilisateur')
            ->setDefinition([
                new InputArgument('username', InputArgument::REQUIRED, 'Nom de compte'),
                new InputArgument('role', InputArgument::REQUIRED, 'Nouveau rôle'),
            ])
        ;
        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($this->lock(null, true)) {
            $username = $input->getArgument('username');

            $user = $this->em->getRepository(User::class)->loadUserByUsername($username);

            $role = $input->getArgument('role');

            $user->setRoles(array($role));

            $errors = $this->validator->validate($user);

            if (count($errors) > 0) {
                $errorsString = (string) $errors;
                throw new \Exception($errorsString);
            }

            $this->em->flush();

            $output->writeln(sprintf('Nouveau rôle défini pour %s', $username));

            parent::execute();
        }
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $questions = array();

        if (!$input->getArgument('username')) {
            $question = new Question('Nom de compte de l\'utilisateur : ');
            $questions['username'] = $question;
        }

        if (!$input->getArgument('role')) {
            $question = new Question('Nouveau rôle : ');
            $questions['role'] = $question;
        }

        foreach ($questions as $name => $question) {
            $answer = $this->getHelper('question')->ask($input, $output, $question);
            $input->setArgument($name, $answer);
        }

        parent::interact($input, $output);
    }
}