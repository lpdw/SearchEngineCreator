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
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

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

    /**
     * @Route("searchEngine/{name}/getResults", name="getResults")
     */
    public function getResultsAction(Request $req)
    {
        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());

        $serializer = new Serializer($normalizers, $encoders);

        $em = $this->getDoctrine()->getManager();

        $name = $req->get('name');
        $results = [];
        $searchValues = $req->request->get('searchValues');

        foreach ($searchValues as $searchValue) {
            $featureCV = $em->getRepository('lpdwSearchEngineBundle:FeatureCategoryValue')->find($searchValue['id']);

            if($searchValue['type'] == 'checkbox' || $searchValue['type'] == 'radio') {
                if($searchValue['checked'] == 'true') {
                    $featureValues = $em->getRepository('lpdwSearchEngineBundle:FeatureValue')->findByFeatureCV($featureCV);
                    foreach($featureValues as $featureValue) {
                        if($name == $featureValue->getElement()->getCategory()->getName()) {
                            $jsonContent = $serializer->serialize($featureValue->getElement(), 'json');
                            array_push($results, $jsonContent);
                            return new JsonResponse($jsonContent);
                        }
                    }
                }
            } else if($searchValue['type'] == 'checkbox'){

            }
        }
    }
}
