<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Presentation;
use AppBundle\Form\PresentationType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;

class PresentationController extends Controller
{
    /**
     * @Route("/presentations", name="presentations")
     * @Method("GET")
     */
    public function listAction()
    {
        $presentations = $this->getDoctrine()->getManager()
            ->getRepository('AppBundle:Presentation')->findAll();

        $categories = $this->getDoctrine()->getManager()
            ->getRepository('AppBundle:Category')->findAll();

        $subcategories = $this->getDoctrine()->getManager()
            ->getRepository('AppBundle:Subcategory')->findAll();

        return $this->render('presentation/list.html.twig', array(
            'presentations' => $presentations,
            'categories'=>$categories,
            'subcategories'=>$subcategories
        ));
    }

    /**
     * @Route("/presentations/add", name="presentations_add")
     * @Method({"GET","POST"})
     */
    public function newAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $newPresentation = new Presentation();

        $presentations = $em->getRepository('AppBundle:Presentation');

        $form = $this->createCreateForm($newPresentation);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->persist($newPresentation);
            $em->flush();

            return $this->redirect($this->generateUrl('presentations'));
        }

        return $this->render('presentation/new.html.twig', array(
            'entity' => $newPresentation,
            'form'   => $form->createView(),
        ));
    }

    /**
     * @Route("/presentations/{id}",name="presentations_show")
     * @Method("GET")
     */
    public function showAction($id)
    {
        $presentations = $this->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:Presentation');
        $presentation = $presentations->find($id);
        return $this->render('presentation/show.html.twig', array(
            'presentation' => $presentation,
        ));
    }



    /**
     * @Route("/presentations/edit/{id}", name="presentations_edit")
     * @Method({"GET", "POST"})
     * @param Presentation $entity
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Presentation $entity, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($entity);

        $editForm->handleRequest($request);

        if( $editForm->isValid()){
            $em->flush();

            return $this->redirect($this->generateUrl('presentations'));
        }

        return $this->render('presentation/edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * @Route("/presentations/{id}", name="presentations_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Presentation $presentation)
    {
        $form = $this->createDeleteForm($presentation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->remove($presentation);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('presentations'));
    }


    private function createCreateForm(Presentation $presentation)
    {
        $form = $this->createForm(new PresentationType(), $presentation);

        $form->add('submit', 'submit', array('label' => 'Добавить группу', 'attr' => array('class' => 'btn btn-default btn-lg btn-block')));

        return $form;
    }

    /**
     * Creates a form to edit a User entity.
     *
     * @param Presentation $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Presentation $entity)
    {
        $form = $this->createForm(new PresentationType(), $entity);

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
    private function createDeleteForm(Presentation $presentation)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('presentations_delete', array('id'=>$presentation->getId())))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Удалить группу', 'attr' => array('class' => 'btn btn-default btn-lg btn-block')))
            ->getForm()
            ;
    }
}
