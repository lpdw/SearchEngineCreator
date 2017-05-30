<?php

namespace lpdw\SearchEngineBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("searchEngine/")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $categories = $em->getRepository('lpdwSearchEngineBundle:Category')->findAll();

        return $this->render('lpdwSearchEngineBundle:Default:index.html.twig', array(
            'categories' => $categories,
        ));
    }
}
