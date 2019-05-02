<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends BaseFixtures
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    public function loadData(ObjectManager $manager)
    {
        $this->createMany(User::class, 1, function(User $user, $count) {
            $user->setUsername('P0wer-Services');
            $password = $this->encoder->encodePassword($user, 'Zc8twW0205S');
            $user->setPassword($password);
            $user->setEmail('Support@p0wer-services.eu');
        });

        $manager->flush();
    }
}
