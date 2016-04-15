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
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
            //'entity' => $newPresentation,
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
            'entity' => $presentation,
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
        $presentations = $em->getRepository('AppBundle:Presentation');

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($entity);

        $editForm->handleRequest($request);

        if( $editForm->isSubmitted() && $editForm->isValid()){
            if ($editForm->get('save')->isClicked()){
                $em->flush();

                return $this->redirect($this->generateUrl('presentations'));
            }
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

        //$form->add('submit', 'submit', array('label' => 'Добавить презентацию', 'attr' => array('class' => 'btn btn-default btn-lg btn-block')));

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

        //$form->add('save', SubmitType::class, array('label' => 'Изменить', 'attr' => array('class' => 'btn btn-default btn-lg btn-block')));

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
     * @Route("/upload_new", name="upload_new")
     */
    public function uploadNewAction(Request $request){
        $em = $this->getDoctrine()->getManager();

        $jsonData = $request->request->get('json');
        $files = $request->files;
        $filenames = explode(',',$request->request->get('filenames'));

        $jsonDecode = json_decode($jsonData, true);
        $serializer = $this->get('jms_serializer');
        $newPresentation = $serializer->deserialize($jsonData, 'AppBundle\Entity\Presentation', 'json');

        $slides = $em->getRepository('AppBundle:Slide');
        $author = $em->getRepository('AppBundle:User')->findByUsername($jsonDecode["author"])[0];

        $presentation = new Presentation();
        $presentation->setName($jsonDecode["name"]);
        $presentation->setTemplate($jsonDecode["template"]);
        $presentation->setAuthor($author);

        $counter = 0;
        foreach($files->all() as $file){
            $slide = new Slide($filenames[$counter]);
            $slide->setFile($file);

            $em->persist($slide);
            $em->flush();

            $counter++;
        }

        foreach ($newPresentation->getCategories() as $newCategory) {
            $category = new Category($newCategory->getName());
            foreach ($newCategory->getSubcategories() as $newSubcategory) {
                $subcategory = new Subcategory($newSubcategory->getName(),$category);
                foreach ($newSubcategory->getSlides() as $newSlide) {
                    $slide = $slides->findOneByName($newSlide->getName());
                    $slide->setNumber($newSlide->getNumber());
                    $slide->setSubcategory($subcategory);
                    $subcategory->addSlide($slide);
                }
                $em->persist($subcategory);
                $category->addSubcategory($subcategory);
            }
            $em->persist($category);
            $presentation->addCategory($category);
        }
        $em->persist($presentation);
        $em->flush();

        return new JsonResponse(array('redirect_url'=> $this->generateUrl('presentations')));
    }

    /**
     * @Route("/upload_edited", name="upload_edited")
     */
    public function uploadEditedAction(Request $request){
        $em = $this->getDoctrine()->getManager();

        $jsonData = $request->request->get('json');
        $files = $request->files;
        $filenames = explode(',',$request->request->get('filenames'));

        $slides = $em->getRepository('AppBundle:Slide');

        $serializer = $this->get('jms_serializer');
        $newPresentation = $serializer->deserialize($jsonData, 'AppBundle\Entity\Presentation', 'json');

        $counter = 0;
        foreach($files->all() as $file){
            $slide = new Slide($filenames[$counter]);
            $slide->setFile($file);

            $em->persist($slide);
            $em->flush();

            $counter++;
        }
                //remove/edit

        $presentation = $em->getRepository('AppBundle:Presentation')->find($newPresentation->getId());
        $presentation->setName($newPresentation->getName());
        $presentation->setTemplate($newPresentation->getTemplate());

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
                    foreach($newSubcategory->getSlides() as $newSlide){
                        $slide = $slides->findOneByName($newSlide->getName());
                        $slide->setNumber($newSlide->getNumber());
                        $slide->setSubcategory($subcategory);
                        $subcategory->addSlide($slide);
                    }
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
                        foreach($newSubcategory->getSlides() as $newSlide){
                            $slide = $slides->findOneByName($newSlide->getName());
                            $slide->setNumber($newSlide->getNumber());
                            $slide->setSubcategory($subcategory);
                            $subcategory->addSlide($slide);
                        }
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

        return new JsonResponse(array('redirect_url'=> $this->generateUrl('presentations')));
    }
}
