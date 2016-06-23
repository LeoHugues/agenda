<?php

namespace UserBundle\DataFixtures\ORM;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use UserBundle\Entity\User;

/**
 * Created by PhpStorm.
 * User: leo
 * Date: 6/23/16
 * Time: 12:55 AM
 */
class LoadUserData implements FixtureInterface
{
    /**
     * @var ObjectManager $manager
     */
    private $manager;
    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;
        $users = new ArrayCollection();
        for ($i = 0; $i < 100; $i++) {
            $user = new User();
            $user->setUsername('user' . $i);
            $user->setEmail('user' . $i . '@book-box.eu');
            $user->setPassword('passUser' . $i);
            $users->add($user);
        }
        
        for ($i = 0; $i < 10; $i++) {
            $this->makeRelashion($users, $users[$i], $i);
        }
        $manager->flush();
    }

    /**
     * @param $users
     * @param $user User
     * @param $position
     */
    private function makeRelashion($users, $user, $position) {
        for ($i = 10; $i < 100; $i = $i + 10) {
            $user->addFriend($users[$i + $position]);
            $this->manager->persist($users[$i + $position]);
        }
        $this->manager->persist($user);
    }

    public function getOrder()
    {
        // the order in which fixtures will be loaded
        // the lower the number, the sooner that this fixture is loaded
        return 1;
    }
}