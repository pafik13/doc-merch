<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Category;
use AppBundle\Entity\Manager;
use AppBundle\Entity\Presentation;
use AppBundle\Entity\Presenter;
use AppBundle\Entity\Slide;
use AppBundle\Entity\Subcategory;
use AppBundle\Entity\Territory;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\User;
use AppBundle\Entity\Role;
use Symfony\Component\Validator\Constraints\DateTime;

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

        $territory1 = new Territory();
        $territory1->setName("Округ1");
        $territory1->setKey("Описание");

        $territory2 = new Territory();
        $territory2->setName("Округ2");
        $territory2->setKey("Описание");

        $manager->persist($territory1);
        $manager->persist($territory2);

        $manager->persist($userAdmin);

        $userUser = new User();
        $userUser->setUsername('user');
        $userUser->setPassword('password');
        $userUser->setEmail('user@user.com');
        $userUser->setRole($role->getId('USER'));

        $manager->persist($userUser);
        $manager->flush();

        $userManager1 = new Manager();
        $userManager1->setUsername('manager1');
        $userManager1->setPassword('password');
        $userManager1->setEmail('manager1@manager1.com');
        $userManager1->setRole($role->getId('MANAGER'));
        $userManager1->setSurname('manager1');
        $userManager1->setName('manager1');
        $userManager1->setPatronymic('manager1');
        $userManager1->setGender('m');
        $userManager1->setBirthday(new \DateTime('1980-01-01'));

        $userManager2 = new Manager();
        $userManager2->setUsername('manager2');
        $userManager2->setPassword('password');
        $userManager2->setEmail('manager2@manager2.com');
        $userManager2->setRole($role->getId('MANAGER'));
        $userManager2->setSurname('manager2');
        $userManager2->setName('manager2');
        $userManager2->setPatronymic('manager2');
        $userManager2->setGender('m');
        $userManager2->setBirthday(new \DateTime('1980-01-01'));

        $manager->persist($userManager1);
        $manager->persist($userManager2);


        $userPresenter1 = new Presenter();
        $userPresenter1->setUsername('presenter1');
        $userPresenter1->setPassword('password1');
        $userPresenter1->setEmail('presenter1@presenter1.com');
        $userPresenter1->setRole($role->getId('PRESENTER'));
        $userPresenter1->setSurname('Иванов');
        $userPresenter1->setName('Иван');
        $userPresenter1->setPatronymic('Иванович');
        $userPresenter1->setGender('m');
        $userPresenter1->setBirthday(new \DateTime('1980-01-01'));
        $userPresenter1->setTerritory($territory1);
        $userPresenter1->setManager($userManager1);

        $userPresenter2 = new Presenter();
        $userPresenter2->setUsername('presenter2');
        $userPresenter2->setPassword('password2');
        $userPresenter2->setEmail('presenter2@presenter2.com');
        $userPresenter2->setRole($role->getId('PRESENTER'));
        $userPresenter2->setSurname('Петров');
        $userPresenter2->setName('Петр');
        $userPresenter2->setPatronymic('Петрович');
        $userPresenter2->setGender('m');
        $userPresenter2->setBirthday(new \DateTime('1980-01-01'));
        $userPresenter2->setManager($userManager2);
        $userPresenter2->setTerritory($territory2);

        $manager->persist($userPresenter1);
        $manager->persist($userPresenter2);

        $manager->flush();


        $category1 = new Category();
        $category1->setName("category1");

        $category2 = new Category();
        $category2->setName("category2");

        $manager->persist($category1);
        $manager->persist($category2);

        $subcategory1 = new Subcategory();
        $subcategory1->setName("subcategory1");
        $subcategory1->setCategory($category1);

        $subcategory2 = new Subcategory();
        $subcategory2->setName("subcategory2");
        $subcategory2->setCategory($category1);

        $subcategory3 = new Subcategory();
        $subcategory3->setName("subcategory3");
        $subcategory3->setCategory($category2);

        $manager->persist($subcategory1);
        $manager->persist($subcategory2);
        $manager->persist($subcategory3);

        $slide1 = new Slide();
        $slide1->setImage("image1.jpg");
        $slide1->setQueue(1);
        $slide1->setSubcategory($subcategory1);

        $slide3 = new Slide();
        $slide3->setImage("image2.jpg");
        $slide3->setQueue(1);
        $slide3->setSubcategory($subcategory2);

        $slide2 = new Slide();
        $slide2->setImage("image3.jpg");
        $slide2->setQueue(1);
        $slide2->setSubcategory($subcategory3);

        $manager->persist($slide1);
        $manager->persist($slide2);
        $manager->persist($slide3);

        $presentation = new Presentation();
        $presentation->setName("presentation");
        $presentation->setAuthor($userManager2);
        $presentation->setDate(new \DateTime());
        $presentation->setTemplate("template");
        $presentation->getCategories()->add($category1);
        $presentation->getCategories()->add($category2);

        $manager->persist($presentation);

        $manager->flush();

    }
}