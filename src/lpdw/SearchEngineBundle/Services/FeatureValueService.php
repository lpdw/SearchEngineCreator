<?php
/**
 * Created by PhpStorm.
 * User: sinki
 * Date: 30/05/2017
 * Time: 14:26
 */

namespace lpdw\SearchEngineBundle\Services;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\BooleanType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class FeatureValueService
{
    private $doctrine;
    public function __construct($doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function newForm($features,$form){
        $em = $this->doctrine;
        $i = 0;

        foreach ($features as $feature) {
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
        return $form;
    }

    public function editForm($features,$form,$element){

        $em = $this->doctrine;
        $i = 0;
        foreach ($features as $feature) {
            if ($feature->getType() == 'TextType') {

                $featureCatVal = $em->getRepository('lpdwSearchEngineBundle:FeatureCategoryValue')->findOneByFeature($feature);
                $featureVal = $em->getRepository('lpdwSearchEngineBundle:FeatureValue')->findByFeatureCVandElement($featureCatVal, $element);
                $form->add('value' . $i, TextType::class, [
                    'label' => $feature->getName(), 'mapped' => false, ['attr' => ['class' => $featureCatVal->getId()]]
                ]);
            }
            if ($feature->getType() == 'NumberType') {

                $featureCatVal = $em->getRepository('lpdwSearchEngineBundle:FeatureCategoryValue')->findOneByFeature($feature);
                $featureVal = $em->getRepository('lpdwSearchEngineBundle:FeatureValue')->findByFeatureCVandElement($featureCatVal, $element);

                $form->add('value' . $i, NumberType::class, [
                    'label' => $feature->getName(), 'mapped' => false, ['attr' => ['class' => $featureCatVal->getId()]]
                ]);
            }
            if ($feature->getType() == 'BooleanType') {
                $featureCatVal = $em->getRepository('lpdwSearchEngineBundle:FeatureCategoryValue')->findOneByFeature($feature);

                $featureVal = $em->getRepository('lpdwSearchEngineBundle:FeatureValue')->findByFeatureCVandElement($featureCatVal, $element);
                $data = [];
                foreach ($featureVal as $item) {
                    $data[$item->getFeatureCV()->getValue()] = $item->getFeatureCV()->getId();

                }
                $originFeature = $featureCatVal[0]->getFeature();
                $form->add('value' . $i, [
                    'label' => $feature->getName(), 'mapped' => false, ['attr' => ['class' => $featureCatVal->getId()]]
                ]);
            }
            if ($feature->getType() == 'RangeType') {
                $featureCatVal = $em->getRepository('lpdwSearchEngineBundle:FeatureCategoryValue')->findOneByFeature($feature);

                $featureVal = $em->getRepository('lpdwSearchEngineBundle:FeatureValue')->findByFeatureCVandElement($featureCatVal, $element);
                $data = [];
                foreach ($featureVal as $item) {
                    $data[$item->getFeatureCV()->getValue()] = $item->getFeatureCV()->getId();

                }

                $values = explode("-", $featureCatVal->getValue());
                $form->add('value' . $i, NumberType::class, [
                    'label' => $feature->getName(),
                    'attr' => ['min' => $values[0],
                        'max' => $values[1]], 'mapped' => false, ['attr' => ['class' => $featureCatVal->getId()]]
                ]);
            }
            if ($feature->getType() == 'checkbox') {
                $featureCatVal = $em->getRepository('lpdwSearchEngineBundle:FeatureCategoryValue')->findByFeature($feature);
                $featureVal = $em->getRepository('lpdwSearchEngineBundle:FeatureValue')->findByFeatureCVandElement($featureCatVal, $element);

                $data = [];
                foreach ($featureVal as $item) {
                    $data[$item->getFeatureCV()->getValue()] = $item->getFeatureCV()->getId();

                }
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
                    'data' => $data
                ]);
            }

            if ($feature->getType() == 'radio') {
                $featureCatVal = $em->getRepository('lpdwSearchEngineBundle:FeatureCategoryValue')->findByFeature($feature);
                $featureVal = $em->getRepository('lpdwSearchEngineBundle:FeatureValue')->findOneByFeatureCVandElement($featureCatVal, $element);
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
                    'data' => $featureVal->getFeatureCV()->getId()
                ]);
            }

            if ($feature->getType() == 'select') {

                $featureCatVal = $em->getRepository('lpdwSearchEngineBundle:FeatureCategoryValue')->findByFeature($feature);
                $featureVal = $em->getRepository('lpdwSearchEngineBundle:FeatureValue')->findOneByFeatureCVandElement($featureCatVal, $element);


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

                    'data' => $featureVal->getFeatureCV()->getId()
                ]);
            }


            $i++;
        }
        return $form;
    }
}