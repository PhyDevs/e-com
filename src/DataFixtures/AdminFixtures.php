<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $admin = new User();
        $admin->setUsername("admin");

        $password = $this->passwordEncoder->encodePassword($admin, "phydevs");
        $roles[] = 'ROLE_ADMIN';

        $admin->setPassword($password)
            ->setEmail("admin@phydevs.com")
            ->setFullName("PhyDev Admin")
            ->setRoles($roles);

        $manager->persist($admin);
        $manager->flush();
    }
}
