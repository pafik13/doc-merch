<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Hospital;
use AppBundle\Form\HospitalType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;

class HospitalController extends Controller
{
    /**
     * @Route("/hospitals", name="hospitals")
     * @Method("GET")
     */
    public function listAction()
    {
        $hospitals = $this->getDoctrine()->getManager()
            ->getRepository('AppBundle:Hospital')->findAll();

        return $this->render('hospital/list.html.twig', array(
            'hospitals' => $hospitals,
        ));
    }

    /**
     * @Route("/hospitals/add", name="hospitals_add")
     * @Method({"GET","POST"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $newHospital = new Hospital();

        $hospitals = $em->getRepository('AppBundle:Hospital');

        $form = $this->createCreateForm($newHospital);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->persist($newHospital);
            $em->flush();

            return $this->redirect($this->generateUrl('hospitals'));
        }

        return $this->render('hospital/new.html.twig', array(
            'entity' => $newHospital,
            'form'   => $form->createView(),
        ));
    }

    /**
     * @Route("/hospitals/edit/{id}", name="hospitals_edit")
     * @Method({"GET", "POST"})
     * @param Hospital $entity
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Hospital $entity, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($entity);

        $editForm->handleRequest($request);

        if( $editForm->isSubmitted() && $editForm->isValid()){
            $em->flush();

            return $this->redirect($this->generateUrl('hospitals'));
        }

        return $this->render('hospital/edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * @Route("/hospitals/{id}", name="hospitals_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Hospital $hospital)
    {
        $form = $this->createDeleteForm($hospital);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->remove($hospital);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('hospitals'));
    }

    /**
     * @Route("/hospitals/{id}",name="hospitals_show")
     * @Method("GET")
     */
    public function showAction($id)
    {
        $hospitals = $this->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:Hospital');
        $hospital = $hospitals->find($id);

        return $this->render('hospital/show.html.twig', array(
            'hospital' => $hospital
        ));
    }

    private function createCreateForm(Hospital $hospital)
    {
        $form = $this->createForm(new HospitalType(), $hospital);

        $form->add('submit', 'submit', array('label' => 'Добавить ЛПУ', 'attr' => array('class' => 'btn btn-default btn-lg btn-block')));

        return $form;
    }

    /**
     * Creates a form to edit a User entity.
     *
     * @param Hospital $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Hospital $entity)
    {
        $form = $this->createForm(new HospitalType(), $entity);

        $form->add('submit', 'submit', array('label' => 'Изменить', 'attr' => array('class' => 'btn btn-default btn-lg btn-block')));

        return $form;
    }

    /**
     * Creates a form to delete a User entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Hospital $hospital)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('hospitals_delete', array('id'=>$hospital->getId())))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Удалить ЛПУ', 'attr' => array('class' => 'btn btn-default btn-lg btn-block')))
            ->getForm()
            ;
    }
}
