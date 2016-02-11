<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
class PresentationController extends Controller
{
    /**
     * @Route("/presentations", name="presentations")
     * @Method("GET")
     */
    public function listAction()
    {
        $items = $this->getDoctrine()->getManager()
            ->getRepository('AppBundle:Presentation')->findAll();

        return $this->render('presentations/list.html.twig', array(
            'presentations' => $items,
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
        return $this->render('presentations/show.html.twig', array(
            'presentation' => $presentation,
        ));
    }
}
