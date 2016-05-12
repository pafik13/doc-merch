<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Subcategory;
use AppBundle\Form\SubcategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;

class SubcategoryController extends Controller
{
    /**
     * @Route("/subcategories", name="subcategories")
     * @Method("GET")
     */
    public function listAction()
    {
        $items = $this->getDoctrine()->getManager()
            ->getRepository('AppBundle:Subcategory')->findAll();

        return $this->render('subcategory/list.html.twig', array(
            'subcategories' => $items,
        ));
    }

    /**
     * @Route("/subcategories/add", name="subcategories_add")
     * @Method({"GET","POST"})
     */
    public function newAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $newSubcategory = new Subcategory();

        $subcategories = $em->getRepository('AppBundle:Subcategory');

        $form = $this->createCreateForm($newSubcategory);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->persist($newSubcategory);
            $em->flush();

            return $this->redirect($this->generateUrl('presentations'));
        }

        return $this->render('subcategory/new.html.twig', array(
            'entity' => $newSubcategory,
            'form'   => $form->createView(),
        ));
    }

    /**
     * @Route("/subcategories/edit/{id}", name="subcategories_edit")
     * @Method({"GET", "POST"})
     * @param Subcategory $entity
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Subcategory $entity, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($entity);

        $editForm->handleRequest($request);

        if( $editForm->isValid()){
            $em->flush();

            return $this->redirect($this->generateUrl('presentations'));
        }

        return $this->render('subcategory/edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * @Route("/subcategories/{id}", name="subcategories_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Subcategory $subcategory)
    {
        $form = $this->createDeleteForm($subcategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->remove($subcategory);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('presentations'));
    }

    /**
     * @Route("/subcategories/{id}",name="subcategories_show")
     * @Method("GET")
     */
    public function showAction($id)
    {
        $subcategories = $this->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:Subcategory');
        $subcategory = $subcategories->find($id);

        return $this->render('subcategory/show.html.twig', array(
            'subcategory' => $subcategory
        ));
    }

    private function createCreateForm(Subcategory $subcategory)
    {
        $form = $this->createForm(new SubcategoryType(), $subcategory);

        $form->add('submit', 'submit', array('label' => 'Добавить группу', 'attr' => array('class' => 'btn btn-default btn-lg btn-block')));

        return $form;
    }

    /**
     * Creates a form to edit a User entity.
     *
     * @param Subcategory $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Subcategory $entity)
    {
        $form = $this->createForm(new SubcategoryType(), $entity);

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
    private function createDeleteForm(Subcategory $subcategory)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('subcategories_delete', array('id'=>$subcategory->getId())))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Удалить группу', 'attr' => array('class' => 'btn btn-default btn-lg btn-block')))
            ->getForm()
            ;
    }
}
