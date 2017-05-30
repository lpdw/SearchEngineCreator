<?php

namespace lpdw\SearchEngineBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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

            $i = 0;
            $form = $this->createFormBuilder();

            foreach($features as $feature) {
                if ($feature->getType() == 'TextType') {
                    $featureCatVal = $em->getRepository('lpdwSearchEngineBundle:FeatureCategoryValue')->findOneByFeature($feature);
                    $form->add('value' . $i, TextType::class, [
                        'label' => $feature->getName(), 'mapped' => false, ['attr' => ['class' => $featureCatVal->getId()]]
                    ]);
                }
                if ($feature->getType() == 'NumberType') {
                    $featureCatVal = $em->getRepository('lpdwSearchEngineBundle:FeatureCategoryValue')->findOneByFeature($feature);

                    $form->add('value' . $i, NumberType::class, [
                        'label' => $feature->getName(), 'mapped' => false, ['attr' => ['class' => $featureCatVal->getId()]]
                    ]);
                }
                if ($feature->getType() == 'BooleanType') {
                    $featureCatVal = $em->getRepository('lpdwSearchEngineBundle:FeatureCategoryValue')->findOneByFeature($feature);

                    $originFeature = $featureCatVal[0]->getFeature();
                    $form->add('value' . $i, [
                        'label' => $feature->getName(), 'mapped' => false, ['attr' => ['class' => $featureCatVal->getId()]]
                    ]);
                }
                if ($feature->getType() == 'RangeType') {
                    $featureCatVal = $em->getRepository('lpdwSearchEngineBundle:FeatureCategoryValue')->findOneByFeature($feature);

                    $values = explode("-", $featureCatVal->getValue());
                    $form->add('value' . $i, NumberType::class, [
                        'label' => $feature->getName(),
                        'attr' => ['min' => $values[0],
                            'max' => $values[1]], 'mapped' => false, ['attr' => ['class' => $featureCatVal->getId()]]
                    ]);
                }
                if ($feature->getType() == 'checkbox') {
                    $featureCatVal = $em->getRepository('lpdwSearchEngineBundle:FeatureCategoryValue')->findByFeature($feature);

                    $tab = [];
                    foreach ($featureCatVal as $key => $value) {
                        $tab[$value->getValue()] = $value->getId();
                    }
                    $form->add('value' . $i, ChoiceType::class, [
                        'label' => $feature->getName(),
                        'choices' => $tab,
                        'expanded' => true,
                        'multiple' => true,
                        'mapped' => false,
                    ]);
                }

                if ($feature->getType() == 'radio') {
                    $featureCatVal = $em->getRepository('lpdwSearchEngineBundle:FeatureCategoryValue')->findByFeature($feature);
                    $tab = [];
                    foreach ($featureCatVal as $key => $value) {
                        $tab[$value->getValue()] = $value->getId();
                    }
                    $form->add('value' . $i, ChoiceType::class, [
                        'label' => $feature->getName(),
                        'choices' => $tab,
                        'expanded' => true,
                        'multiple' => false,
                        'mapped' => false,
                    ]);
                }

                if ($feature->getType() == 'select') {
                    $featureCatVal = $em->getRepository('lpdwSearchEngineBundle:FeatureCategoryValue')->findByFeature($feature);
                    $tab = [];
                    foreach ($featureCatVal as $key => $value) {
                        $tab[$value->getValue()] = $value->getId();
                    }
                    $form->add('value' . $i, ChoiceType::class, [
                        'label' => $feature->getName(),
                        'choices' => $tab,
                        'expanded' => false,
                        'multiple' => false,
                        'mapped' => false,
                    ]);
                }


                $i++;
            }

            return $this->render('lpdwSearchEngineBundle:Default:index.html.twig', array(
                'form' => $form->getForm()->createView(),
            ));
        }
    }
}
