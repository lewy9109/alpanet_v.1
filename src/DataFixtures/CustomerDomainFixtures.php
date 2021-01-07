<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\CustomerDomain;
use App\Entity\User;
class CustomerDomainFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        foreach($this->getUserData() as [$id, $name_domain])
        {
            $user = $manager->getRepository(User::class)->find($id);
            $domains = new CustomerDomain();
            
            $domains->setUser($user);
            $domains->setNameDomain($name_domain);
            
            $manager->persist($domains);
        }

        $manager->flush();
    }

    private function getUserData():array
    {
      
        return[
            [4, 'domena.pl'],
            [5, 'domena2.pl'],
            [6, 'domena3.pl'],
            
        ];
    }
}
