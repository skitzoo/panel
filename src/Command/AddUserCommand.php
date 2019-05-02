<?php

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AddUserCommand extends Command
{
    protected static $defaultName = "app:UserCreate";

    /**
     * @var SymfonyStyle
     */
    private $io;

    private $entityManager;
    private $passwordEncoder;
    private $validator;
    private $users;

    public function __construct(EntityManagerInterface $em, UserPasswordEncoderInterface $encoder, ValidatorInterface $validator, UserRepository $users, ?string $name = null)
    {
        parent::__construct();

        $this->entityManager = $em;
        $this->passwordEncoder = $encoder;
        $this->validator = $validator;
        $this->users = $users;
    }

    protected function configure()
    {
        parent::configure();

        $this
            ->setDefinition([
                new InputArgument('username', InputArgument::REQUIRED, 'Nom de compte'),
                new InputArgument('password', InputArgument::REQUIRED, 'Mot de passe'),
                new InputArgument('email', InputArgument::REQUIRED, 'Adresse Mail'),
            ])
        ;
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        parent::initialize($input, $output);

        $this->io = new SymfonyStyle($input, $output);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $user = new User();

        $username = $input->getArgument('username');

        $password = $input->getArgument('password');
        $password = $this->passwordEncoder->encodePassword($user, $password);

        $email = $input->getArgument('email');

        $user->setUsername($username);
        $user->setPassword($password);
        $user->setEmail($email);

        $errors = $this->validator->validate($user);

        if (count($errors) > 0) {
            $errorsString = (string) $errors;
            throw new \Exception($errorsString);
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $output->writeln(sprintf('Utilisateur crée : %s', $username));
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        parent::interact($input, $output);

        if (null !== $input->getArgument("username") && null !== $input->getArgument("password") && null !== $input->getArgument("email"))
        {
            return;
        }
        $questions = array();

        if (!$input->getArgument('username')) {
            $question = new Question('Merci de choisir un nom de compte : ');
            $questions['username'] = $question;
        }

        if (!$input->getArgument('password')) {
            $question = new Question('Merci de choisir un mot de passe : ');
            $question->setHidden(true);
            $question->setHiddenFallback(false);
            $questions['password'] = $question;
        }

        if (!$input->getArgument('email')) {
            $question = new Question('Merci de choisir une adresse mail : ');
            $questions['email'] = $question;
        }

        foreach ($questions as $name => $question) {
            $answer = $this->getHelper('question')->ask($input, $output, $question);
            $input->setArgument($name, $answer);
        }
    }
}