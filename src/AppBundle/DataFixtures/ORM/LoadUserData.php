<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\User;
use AppBundle\Entity\Role;

class LoadUserData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $role = new Role();

        $userAdmin = new User();
        $userAdmin->setUsername('admin');
        $userAdmin->setPassword('password');
        $userAdmin->setEmail('admin@admin.com');
        $userAdmin->setRole($role->getId('ADMIN'));
        $userAdmin->setSurname('admin');
        $userAdmin->setName('admin');
        $userAdmin->setPatronymic('admin');
        $userAdmin->setGender('m');
        $userAdmin->setBirthday(new \DateTime('1900-01-01'));
        $userAdmin->setDistrict('admin');
        $userAdmin->setManager('admin');

        $manager->persist($userAdmin);

        $userManager1 = new User();
        $userManager1->setUsername('manager1');
        $userManager1->setPassword('password');
        $userManager1->setEmail('manager1@manager1.com');
        $userManager1->setRole($role->getId('MANAGER'));
        $userManager1->setSurname('manager1');
        $userManager1->setName('manager1');
        $userManager1->setPatronymic('manager1');
        $userManager1->setGender('m');
        $userManager1->setBirthday(new \DateTime('1900-01-01'));
        $userManager1->setDistrict('manager1');
        $userManager1->setManager('manager1');

        $manager->persist($userManager1);

        $userManager2 = new User();
        $userManager2->setUsername('manager2');
        $userManager2->setPassword('password');
        $userManager2->setEmail('manager2@manager2.com');
        $userManager2->setRole($role->getId('MANAGER'));
        $userManager2->setSurname('manager2');
        $userManager2->setName('manager2');
        $userManager2->setPatronymic('manager2');
        $userManager2->setGender('m');
        $userManager2->setBirthday(new \DateTime('1900-01-01'));
        $userManager2->setDistrict('manager2');
        $userManager2->setManager('manager2');

        $manager->persist($userManager2);

        $userUser = new User();
        $userUser->setUsername('user');
        $userUser->setPassword('password');
        $userUser->setEmail('user@user.com');
        $userUser->setRole($role->getId('USER'));
        $userUser->setSurname('user');
        $userUser->setName('user');
        $userUser->setPatronymic('user');
        $userUser->setGender('m');
        $userUser->setBirthday(new \DateTime('1900-01-01'));
        $userUser->setDistrict('user');
        $userUser->setManager('user');

        $manager->persist($userUser);
        $manager->flush();

        $id1 = $manager->getRepository('AppBundle:User')->findByUsername('manager1')[0]->getId();
        $id2 = $manager->getRepository('AppBundle:User')->findByUsername('manager2')[0]->getId();

        $userPresenter1 = new User();
        $userPresenter1->setUsername('presenter1');
        $userPresenter1->setPassword('password1');
        $userPresenter1->setEmail('presenter1@presenter1.com');
        $userPresenter1->setRole($role->getId('PRESENTER'));
        $userPresenter1->setSurname('presenter1');
        $userPresenter1->setName('presenter1');
        $userPresenter1->setPatronymic('presenter1');
        $userPresenter1->setGender('m');
        $userPresenter1->setBirthday(new \DateTime('1900-01-01'));
        $userPresenter1->setDistrict('presenter1');
        $userPresenter1->setManager($id1);

        $manager->persist($userPresenter1);

        $userPresenter2 = new User();
        $userPresenter2->setUsername('presenter2');
        $userPresenter2->setPassword('password2');
        $userPresenter2->setEmail('presenter2@presenter2.com');
        $userPresenter2->setRole($role->getId('PRESENTER'));
        $userPresenter2->setSurname('presenter2');
        $userPresenter2->setName('presenter2');
        $userPresenter2->setPatronymic('presenter2');
        $userPresenter2->setGender('m');
        $userPresenter2->setBirthday(new \DateTime('1900-01-01'));
        $userPresenter2->setDistrict('presenter2');
        $userPresenter2->setManager($id1);

        $manager->persist($userPresenter2);

        $userPresenter3 = new User();
        $userPresenter3->setUsername('presenter3');
        $userPresenter3->setPassword('presenter3');
        $userPresenter3->setEmail('presenter3@presenter3.com');
        $userPresenter3->setRole($role->getId('PRESENTER'));
        $userPresenter3->setSurname('presenter3');
        $userPresenter3->setName('presenter3');
        $userPresenter3->setPatronymic('presenter3');
        $userPresenter3->setGender('m');
        $userPresenter3->setBirthday(new \DateTime('1900-01-01'));
        $userPresenter3->setDistrict('presenter3');
        $userPresenter3->setManager($id2);

        $manager->persist($userPresenter3);
        $manager->flush();
    }
}