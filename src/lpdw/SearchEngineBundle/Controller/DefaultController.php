<?php

namespace lpdw\SearchEngineBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\BooleanType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class DefaultController extends Controller
{
    /**
     * @Route("searchEngine/", name="home")
     */
    public function indexAction(Request $req)
    {
        $em = $this->getDoctrine()->getManager();

        $categories = $em->getRepository('lpdwSearchEngineBundle:Category')->findAll();

        return $this->render('lpdwSearchEngineBundle:Default:index.html.twig', array(
            'categories' => $categories,
        ));
    }

    /**
     * @Route("searchEngine/{name}", name="generateForm")
     */
    public function generateFormAction(Request $req, $name)
    {
        $em = $this->getDoctrine()->getManager();

        $category = $em->getRepository('lpdwSearchEngineBundle:Category')->findByName($name);
        $features = $em->getRepository('lpdwSearchEngineBundle:Feature')->findByCategory($category);

        $form = $this->createFormBuilder();

        $form = $this->get("app.featureValService")->newForm($features,$form);

        return $this->render('lpdwSearchEngineBundle:Default:step2.html.twig', array(
            'form' => $form->getForm()->createView(),
        ));
    }
}
