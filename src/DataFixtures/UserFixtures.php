<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;

class UserFixtures extends Fixture
{
    public function __construct(UserPasswordEncoderInterface $password_encoder)
    {
        $this->password_encoder = $password_encoder;
    }
    public function load(ObjectManager $manager)
    {
        foreach($this->getUserData() as [$email, $password, $name, $surname,  $roles, $company_name, $phone, $date_add, $img])
        {
            $user = new User();
            $user->setName($name);
            $user->setSurname($surname);
            $user->setEmail($email);
            $user->setRoles($roles);
            $user->setCompanyName($company_name);
            $user->setPhone($phone);
            $user->setImg($img);
            $user->setDateAdd($date_add);
            $user->setPassword($this->password_encoder->encodePassword($user, $password));
            $manager->persist($user);
        }

        $manager->flush();
    }

    private function getUserData():array
    {
        $date = new \DateTime();
        return[
            ['lewy@gmail.com', '123456', 'kryst', 'lewand', ['ROLE_ADMIN'], 'firma', '456', $date, '/img.png']
        ];
    }
}
