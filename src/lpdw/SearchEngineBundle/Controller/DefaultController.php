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
     * @Route("searchEngine/generateForm", name="generateForm")
     */
    public function generateFormAction(Request $req)
    {
        if($req->isXmlHttpRequest()) {
            $category_id = $req->get('categoryId');

            $em = $this->getDoctrine()->getManager();

            $category = $em->getRepository('lpdwSearchEngineBundle:Category')->find($category_id);
            $features = $em->getRepository('lpdwSearchEngineBundle:Feature')->findByCategory($category);

            if(empty($features)) {
                return new Response('Error : no features');
            }

            $form = $this->createFormBuilder();

            $form = $this->get("app.featureValService")->newForm($features,$form);

            return new JsonResponse($form);

            $fields = ['fields' => []];

            foreach ($form->all() as $field) {
                $name = $field->getName();
                $type = $field->getConfig()->getType()->getBlockPrefix();
                $fields['fields'][$name] = ['type' => $type];
            }

            return new JsonResponse(json_encode($fields, JSON_PRETTY_PRINT));
        }
    }
}
