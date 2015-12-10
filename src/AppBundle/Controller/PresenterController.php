<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\User;
use AppBundle\Entity\Gen;
use AppBundle\Entity\Role;
use AppBundle\Form\StaffType;

class PresenterController extends Controller
{
    /**
     * Lists all User entities.
     *
     * @Route("/presenters", name="presenters")
     * @Method("GET")
     */
    public function listAction()
    {
        $users = $this->getDoctrine()
			->getManager()
			->getRepository('AppBundle:User');

		$id = $this->getUser()->getId();
		$role = new Role();
        $my = $users->findByManager($id);
        $other = $users->createQueryBuilder('p')
			->where('p.role = :role AND NOT p.manager = :id')
			->setParameter('id', $id)
			->setParameter('role', $role->getId('PRESENTER'))
			->getQuery()
			->getResult();

        return $this->render('presenter/list.html.twig', array(
            'my' => $my,
			'other' => $other
        ));
    }

    /**
     * Creates a new User entity.
     *
     * @Route("/presenters", name="presenters_create")
     * @Method("POST")
     */
    public function createAction(Request $request)
    {
		$role = new Role();
		$gen = new Gen();
        $entity = new User();

		$em = $em = $this->getDoctrine()->getManager();

		$data = $request->request->get('appbundle_user');
		$username = $gen->genUsername($data['surname'],
                                      $data['name'],
                                      $data['patronymic']);
		$users = $em->getRepository('AppBundle:User');
		while($users->findByUsername($username)) {
			if(!isset($pers_numb)) {$pers_numb = 1; $susername = $username;}
			$username = $susername.$pers_numb;
			$pers_numb++;
		}

		$entity->setRole($role->getId('PRESENTER'));
		$entity->setManager($this->getUser()->getId());
		$entity->setUsername($username);
		$entity->setPassword($gen->genPassword());

        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('presenters'));
        }

        return $this->render('presenter/new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a User entity.
     *
     * @param User $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(User $entity)
    {
        $form = $this->createForm(new StaffType(), $entity, array(
            'action' => $this->generateUrl('presenters_create'),
            'method' => 'POST'
        ));

        $form->add('submit', 'submit', array('label' => 'Добавить представителя', 'attr' => array('class' => 'btn btn-default btn-lg btn-block')));

        return $form;
    }

    /**
     * Displays a form to create a new User entity.
     *
     * @Route("/presenters/add", name="presenters_new")
     * @Method("GET")
     */
    public function newAction()
    {
        $entity = new User();
        $form   = $this->createCreateForm($entity);

        return $this->render('presenter/new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing User entity.
     *
     * @Route("/presenters/edit/{id}", name="presenters_edit")
     * @Method("GET")
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('presenter/edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a User entity.
    *
    * @param User $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(User $entity)
    {
        $form = $this->createForm(new StaffType(), $entity, array(
            'action' => $this->generateUrl('presenters_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Изменить', 'attr' => array('class' => 'btn btn-default btn-lg btn-block')));

        return $form;
    }
    /**
     * Edits an existing User entity.
     *
     * @Route("/presenters/{id}", name="presenters_update")
     * @Method("PUT")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('presenters'));
        }

        return $this->render('presenter/edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a User entity.
     *
     * @Route("/presenters/{id}", name="presenters_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AppBundle:User')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find User entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('presenters'));
    }

    /**
     * Creates a form to delete a User entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('presenters_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Удалить представителя', 'attr' => array('class' => 'btn btn-default btn-lg btn-block')))
            ->getForm()
        ;
    }

    /**
     * Displays some info about presenter.
     *
     * @Route("/presenters/{id}", name="presenters_info")
     * @Method("GET")
     */
    public function infoAction($id)
    {
		$users = $this->getDoctrine()
			->getManager()
			->getRepository('AppBundle:User');
        $presenter = $users->find($id);
		$manager = $users->find($presenter->getManager());

        return $this->render('presenter/info.html.twig', array(
            'presenter' => $presenter,
			'manager' => $manager
        ));
    }
}