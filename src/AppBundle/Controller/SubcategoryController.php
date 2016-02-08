<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
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

        return $this->render('subcategories/list.html.twig', array(
            'subcategories' => $items,
        ));
    }
}
