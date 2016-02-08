<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
class SlideController extends Controller
{
    /**
     * @Route("/slides", name="slides")
     * @Method("GET")
     */
    public function listAction()
    {
        $items = $this->getDoctrine()->getManager()
            ->getRepository('AppBundle:Slide')->findAll();

        return $this->render('slide/list.html.twig', array(
            'items' => $items,
        ));
    }
}
