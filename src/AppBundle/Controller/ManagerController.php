<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Gen;
use AppBundle\Form\ManagerType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use AppBundle\Entity\Manager;
use AppBundle\Entity\Role;
use Symfony\Component\HttpFoundation\Request;

class ManagerController extends Controller
{
    /**
     * @Route("/managers", name="managers")
     * @Method("GET")
     */
    public function listAction()
    {
        $users = $this->getDoctrine()->getManager()
            ->getRepository('AppBundle:Manager')->findAll();

        return $this->render('manager/list.html.twig', array(
            'users' => $users,
        ));
    }

    /**
     * @Route("/managers/add", name="managers_add")
     * @Method({"GET","POST"})
     */
    public function newAction(Request $request)
    {
        $role = new Role();
        $gen = new Gen();
        $newManager = new Manager();

        $em = $this->getDoctrine()->getManager();

        $data = $request->request->get('appbundle_presenter');
        $username = $gen->genUsername($data['surname'],
            $data['name'],
            $data['patronymic']);
        $users = $em->getRepository('AppBundle:Presenter');
        while($users->findByUsername($username)) {
            if(!isset($pers_numb)) {$pers_numb = 1; $susername = $username;}
            $username = $susername.$pers_numb;
            $pers_numb++;
        }

        $newManager->setRole($role->getId('MANAGER'));
        $newManager->setUsername($username);
        $newManager->setPassword($gen->genPassword());

        $form = $this->createCreateForm($newManager);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->persist($newManager);
            $em->flush();

            return $this->redirect($this->generateUrl('managers'));
        }

        return $this->render('manager/new.html.twig', array(
            'entity' => $newManager,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a User entity.
     *
     * @param User $manager The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Manager $manager)
    {
        $form = $this->createForm(new ManagerType(), $manager);

        $form->add('submit', 'submit', array('label' => 'Добавить менеджера', 'attr' => array('class' => 'btn btn-default btn-lg btn-block')));

        return $form;
    }
    /**
     * @Route("/managers/edit/{id}",name="managers_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Manager $entity, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($entity);

        $editForm->handleRequest($request);

        if( $editForm->isValid()){
            $em->flush();

            return $this->redirect($this->generateUrl('managers'));
        }

        return $this->render('manager/edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a User entity.
     *
     * @param Manager $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Manager $entity)
    {
        $form = $this->createForm(new ManagerType(), $entity);

        $form->add('submit', 'submit', array('label' => 'Изменить', 'attr' => array('class' => 'btn btn-default btn-lg btn-block')));

        return $form;
    }

    /**
     * @Route("/managers/{id}", name="managers_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Manager $manager)
    {
        $form = $this->createDeleteForm($manager);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->remove($manager);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('managers'));
    }

    /**
     * Creates a form to delete a User entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Manager $manager)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('managers_delete', array('id'=>$manager->getId())))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Удалить менеджера', 'attr' => array('class' => 'btn btn-default btn-lg btn-block')))
            ->getForm()
            ;
    }

    /**
     * @Route("/managers/{id}", name="managers_show")
     * @Method("GET")
     */
    public function showAction($id)
    {
        $users = $this->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:Manager');
        $manager = $users->find($id);
        //$manager = $users->find($presenter->getManager());

        return $this->render('manager/show.html.twig', array(
            'manager' => $manager
        ));
    }

}
