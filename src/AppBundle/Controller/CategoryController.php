<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;

class CategoryController extends Controller
{
    /**
     * @Route("/categories", name="categories")
     * @Method("GET")
     */
    public function listAction()
    {
        $items = $this->getDoctrine()->getManager()
            ->getRepository('AppBundle:Category')->findAll();

        return $this->render('category/list.html.twig', array(
            'categories' => $items,
        ));
    }

    /**
     * @Route("/categories/add", name="categories_add")
     * @Method({"GET","POST"})
     */
    public function newAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $newCategory = new Category();

        $categories = $em->getRepository('AppBundle:Category');

        $form = $this->createCreateForm($newCategory);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->persist($newCategory);
            $em->flush();

            return $this->redirect($this->generateUrl('categories'));
        }

        return $this->render('category/new.html.twig', array(
            'entity' => $newCategory,
            'form'   => $form->createView(),
        ));
    }

    /**
     * @Route("/categories/edit/{id}", name="categories_edit")
     * @Method({"GET", "POST"})
     * @param Category $entity
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Category $entity, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($entity);

        $editForm->handleRequest($request);

        if( $editForm->isValid()){
            $em->flush();

            return $this->redirect($this->generateUrl('categories'));
        }

        return $this->render('category/edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * @Route("/categories/{id}", name="categories_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Category $category)
    {
        $form = $this->createDeleteForm($category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->remove($category);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('categories'));
    }

    /**
     * @Route("/categories/{id}",name="categories_show")
     * @Method("GET")
     */
    public function showAction($id)
    {
        $categories = $this->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:Category');
        $category = $categories->find($id);

        return $this->render('category/show.html.twig', array(
            'category' => $category
        ));
    }

    private function createCreateForm(Category $category)
    {
        $form = $this->createForm(new CategoryType(), $category);

        $form->add('submit', 'submit', array('label' => 'Добавить группу', 'attr' => array('class' => 'btn btn-default btn-lg btn-block')));

        return $form;
    }

    /**
     * Creates a form to edit a User entity.
     *
     * @param Category $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Category $entity)
    {
        $form = $this->createForm(new CategoryType(), $entity);

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
    private function createDeleteForm(Category $category)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('categories_delete', array('id'=>$category->getId())))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Удалить группу', 'attr' => array('class' => 'btn btn-default btn-lg btn-block')))
            ->getForm()
            ;
    }    
}
