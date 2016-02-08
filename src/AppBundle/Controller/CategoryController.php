<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
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

        return $this->render('categories/list.html.twig', array(
            'categories' => $items,
        ));
    }
}
