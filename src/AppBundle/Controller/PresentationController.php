<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Entity\Presentation;
use AppBundle\Entity\Slide;
use AppBundle\Entity\Subcategory;
use AppBundle\Form\PresentationType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\VarDumper\VarDumper;

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
     * @Route("/ajax", name="presentations_ajax")
     * @Method({"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function getAjaxAction(Request $request){
        $data = $request->request->get('data','null');
        $id = $request->request->get('id','null');

        $em = $this->getDoctrine()->getManager();

        $presentation = $em->getRepository('AppBundle:Presentation')->find($id);
        $slides = $em->getRepository('AppBundle:Slide');
        $serializer = $this->get('jms_serializer');
        $newPresentation = $serializer->deserialize($data, 'AppBundle\Entity\Presentation', 'json');

        //remove/edit

        foreach($presentation->getCategories() as $category){
            $editedCategory = $newPresentation->findCategoryById($category->getId());
            if(!$editedCategory){
                $presentation->removeCategory($category);
                $em->remove($category);
                $em->flush();
            }
            else {
                if($editedCategory->getName()!=$category->getName()){
                    $category->setName($editedCategory->getName());
                }
                foreach($category->getSubcategories() as $subcategory){
                    $editedSubcategory = $editedCategory->findSubcategoryById($subcategory->getId());
                    if(!$editedSubcategory){
                        $category->removeSubcategory($subcategory);
                    } else {
                        if($editedSubcategory->getName()!=$subcategory->getName()){
                            $subcategory->setName($editedSubcategory->getName());
                        }
                        foreach($subcategory->getSlides() as $slide){
                            $editedSlide = $editedSubcategory->findSlideById($slide->getId());
                            if(!$editedSlide){
                                $subcategory->removeSlide($slide);
                            }
                        }
                    }
                }
            }
        }

       //add

        foreach($newPresentation->getCategories() as $newCategory){
            if(!$presentation->findCategoryById($newCategory->getId())){
                $category = new Category($newCategory->getName());

                foreach($newCategory->getSubcategories() as $newSubcategory){
                    $subcategory = new Subcategory($newSubcategory->getName(),$category);
                    $em->persist($subcategory);
                    $category->addSubcategory($subcategory);
                }
                $em->persist($category);
                $presentation->addCategory($category);
            } else {
                $editedCategory = $presentation->findCategoryById($newCategory->getId());
                foreach($newCategory->getSubcategories() as $newSubcategory){
                    if(!$editedCategory->findSubcategoryById($newSubcategory->getId())){
                        $subcategory = new Subcategory($newSubcategory->getName(),$editedCategory);
                        $em->persist($subcategory);
                        $editedCategory->addSubcategory($subcategory);
                    }
                    else {
                        $editedSubcategory = $editedCategory->findSubcategoryById($newSubcategory->getId());
                        foreach($newSubcategory->getSlides() as $newSlide){
                            if(!$editedSubcategory->findSlideById($newSlide->getId())){
                                $slide = $slides->findOneByName($newSlide->getName());
                                $slide->setNumber($newSlide->getNumber());
                                $slide->setSubcategory($editedSubcategory);

                                $editedSubcategory->addSlide($slide);
                            }
                        }
                    }
                }
            }
        }

        $em->flush();
        $em->close();
        $em = $this->getDoctrine()->getManager();

        $presentation = $em->getRepository('AppBundle:Presentation')->find($id);
        return new Response($serializer->serialize($presentation, 'json'));
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
        $categories = $this->getDoctrine()->getManager()
            ->getRepository('AppBundle:Category')->findAll();

        $subcategories = $this->getDoctrine()->getManager()
            ->getRepository('AppBundle:Subcategory')->findAll();

        $presentation = $presentations->find($id);
        return $this->render('presentation/show.html.twig', array(
            'presentation' => $presentation,
            'categories'=>$categories,
            'subcategories'=>$subcategories
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

    /**
     * @Route("/upload", name="upload")
     */
    public function uploadAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $slides = $em->getRepository('AppBundle:Slide');
        $files = $request->files;

        foreach($files->all() as $file){
            $slide = new Slide($file->getClientOriginalName());
            $slide->setFile($file);
            $slide->upload();

            $em->persist($slide);
            $em->flush();
        }

        return new Response('<img src="'.$slide->getWebPath().'">');
    }
}
