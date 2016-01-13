<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Territory;
use AppBundle\Form\TerritoryType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;

class TerritoryController extends Controller
{
    /**
     * @Route("/territories", name="territories")
     * @Method("GET")
     */
    public function listAction()
    {
        $territories = $this->getDoctrine()->getManager()
            ->getRepository('AppBundle:Territory')->findAll();

        return $this->render('territory/list.html.twig', array(
            'territories' => $territories,
        ));
    }

    /**
     * @Route("/territories/add", name="territories_add")
     * @Method({"GET","POST"})
     */
    public function newAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $newTerritory = new Territory();

        $territories = $em->getRepository('AppBundle:Territory');

        $form = $this->createCreateForm($newTerritory);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->persist($newTerritory);
            $em->flush();

            return $this->redirect($this->generateUrl('territories'));
        }

        return $this->render('territory/new.html.twig', array(
            'entity' => $newTerritory,
            'form'   => $form->createView(),
        ));
    }

    /**
     * @Route("/territories/edit/{id}", name="territories_edit")
     * @Method({"GET", "POST"})
     * @param Territory $entity
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Territory $entity, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($entity);

        $editForm->handleRequest($request);

        if( $editForm->isValid()){
            $em->flush();

            return $this->redirect($this->generateUrl('territories'));
        }

        return $this->render('territory/edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * @Route("/territories/{id}", name="territories_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Territory $territory)
    {
        $form = $this->createDeleteForm($territory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->remove($territory);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('territories'));
    }

    /**
     * @Route("/territories/{id}",name="territories_show")
     * @Method("GET")
     */
    public function showAction($id)
    {
        $territories = $this->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:Territory');
        $territory = $territories->find($id);

        return $this->render('territory/show.html.twig', array(
            'territory' => $territory
        ));
    }

    private function createCreateForm(Territory $territory)
    {
        $form = $this->createForm(new TerritoryType(), $territory);

        $form->add('submit', 'submit', array('label' => 'Добавить округ', 'attr' => array('class' => 'btn btn-default btn-lg btn-block')));

        return $form;
    }

    /**
     * Creates a form to edit a User entity.
     *
     * @param Territory $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Territory $entity)
    {
        $form = $this->createForm(new TerritoryType(), $entity);

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
    private function createDeleteForm(Territory $territory)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('territories_delete', array('id'=>$territory->getId())))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Удалить округ', 'attr' => array('class' => 'btn btn-default btn-lg btn-block')))
            ->getForm()
            ;
    }
}
