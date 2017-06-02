<?php
/**
 * Created by PhpStorm.
 * User: sinki
 * Date: 30/05/2017
 * Time: 14:26
 */

namespace lpdw\SearchEngineBundle\Services;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NoResultException;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
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

    /**
     *  return new featureValue Form
     */
    public function newForm($features,$form){
        $em = $this->doctrine;
        $i = 0;

        foreach ($features as $feature) {
            if ($feature->getType() == 'TextType') {
                $featureCatVal = $em->getRepository('lpdwSearchEngineBundle:FeatureCategoryValue')->findOneByFeature($feature);
                $form->add('value' . $i, TextType::class, [
                    'label' => $feature->getName(),'mapped' => false, ['attr' => ['class' => $featureCatVal->getId()]]
                ]);
            }
            if ($feature->getType() == 'NumberType') {
                $featureCatVal = $em->getRepository('lpdwSearchEngineBundle:FeatureCategoryValue')->findOneByFeature($feature);

                $form->add('value' . $i, NumberType::class, [
                    'label' => false,'mapped' => false, ['attr' => ['class' => $featureCatVal->getId(), 'placeholder' => $feature->getName()]]
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
                $form->add('value' . $i . 'RangeType1'.$featureCatVal->getId(), IntegerType::class, [
                    'label' => false,
                    'required' => true,
                    'mapped' => false,
                    'attr' => [
                        'min' => (int)$values[0],
                        'max' => (int)$values[1],
                        'class'=> $featureCatVal->getId()." fontClemente pull-left inlineBlock mRight10",
                        'placeholder' => $feature->getName()." min"
                    ],

                ]);
                $form->add('value' . $i . 'RangeType2'.$featureCatVal->getId(), IntegerType::class, [
                    'label' => false,
                    'required' => true,
                    'mapped' => false,
                    'attr' => [
                        'min' => (int)$values[0],
                        'max' => (int)$values[1],
                        'class'=> $featureCatVal->getId()." fontClemente",
                        'placeholder' => $feature->getName()." max"
                    ],

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
                $tab[''] = 'default';
                foreach ($featureCatVal as $key => $value) {
                    $tab[$value->getValue()] = $value->getId();
                }
                $form->add('value' . $i, ChoiceType::class, [
                    'label' => $feature->getName(),
                    'label_attr' => ['class' => 'displayBlock'],
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

    /**
     *  return edit featureValue Form
     */
    public function editForm($features,$form,$element){

        $em = $this->doctrine;
        $i = 0;
        foreach ($features as $feature) {
            if ($feature->getType() == 'TextType') {
                $featureCatVal = $em->getRepository('lpdwSearchEngineBundle:FeatureCategoryValue')->findOneByFeature($feature);
                $featureVal = $em->getRepository('lpdwSearchEngineBundle:FeatureValue')->findByFeatureCVandElement($featureCatVal, $element);
                $form->add('value' . $i, TextType::class, [
                    'label' => $feature->getName(), 'required' => true,'mapped' => false, ['attr' => ['class' => $featureCatVal->getId()]]
                ]);
            }
            if ($feature->getType() == 'NumberType') {

                $featureCatVal = $em->getRepository('lpdwSearchEngineBundle:FeatureCategoryValue')->findOneByFeature($feature);
                $featureVal = $em->getRepository('lpdwSearchEngineBundle:FeatureValue')->findByFeatureCVandElement($featureCatVal, $element);

                $form->add('value' . $i, NumberType::class, [
                    'label' => $feature->getName(), 'required' => true, ['attr' => ['class' => $featureCatVal->getId()]]
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
                    'label' => $feature->getName(), 'required' => true, 'mapped' => false, ['attr' => ['class' => $featureCatVal->getId()]]
                ]);
            }
            if ($feature->getType() == 'RangeType') {
                $featureCatVal = $em->getRepository('lpdwSearchEngineBundle:FeatureCategoryValue')->findOneByFeature($feature);

                $values = explode("-", $featureCatVal->getValue());
                try{
                    $featureVal = $em->getRepository('lpdwSearchEngineBundle:FeatureValue')->findOneByFeatureCVandElement($featureCatVal, $element);

                    $value = explode("-", $featureVal->getValue());
                } catch (NoResultException $nr){
                    $value = [];
                    $value[0] = (int)$values[0];
                    $value[1] = (int)$values[1];
                }
                $form->add('value' . $i . 'RangeType1'.$featureCatVal->getId(), IntegerType::class, [
                    'label' => false,
                    'required' => true,
                    'mapped' => false,
                    'attr' => [
                        'min' => (int)$values[0],
                        'max' => (int)$values[1],
                        'class'=> $featureCatVal->getId()." fontClemente pull-left inlineBlock mRight10",
                    ],
                    'data' => $value[0],
                ]);
                $form->add('value' . $i . 'RangeType2'.$featureCatVal->getId(), IntegerType::class, [
                    'label' => false,
                    'required' => true,
                    'mapped' => false,
                    'attr' => [
                        'min' => (int)$values[0],
                        'max' => (int)$values[1],
                        'class'=> $featureCatVal->getId()." fontClemente",
                    ],

                    'data' => $value[1],
                ]);
            }
            if ($feature->getType() == 'checkbox') {
                $featureCatVal = $em->getRepository('lpdwSearchEngineBundle:FeatureCategoryValue')->findByFeature($feature);
                try{
                    $featureVal = $em->getRepository('lpdwSearchEngineBundle:FeatureValue')->findByFeatureCVandElement($featureCatVal, $element);
                    $data = [];
                    foreach ($featureVal as $item) {
                        $data[$item->getFeatureCV()->getValue()] = $item->getFeatureCV()->getId();

                    }
                } catch (NoResultException $nr){
                    $data = "";
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
                    'required' => true,
                    'data' => $data
                ]);
            }

            if ($feature->getType() == 'radio') {
                $featureCatVal = $em->getRepository('lpdwSearchEngineBundle:FeatureCategoryValue')->findByFeature($feature);
                try{
                    $featureVal = $em->getRepository('lpdwSearchEngineBundle:FeatureValue')->findOneByFeatureCVandElement($featureCatVal, $element);
                    $id = $featureVal->getFeatureCV()->getId();
                } catch (NoResultException $nr){
                    $id = "";
                }
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
                    'required' => true,
                    'data' => $id
                ]);
            }

            if ($feature->getType() == 'select') {

                $featureCatVal = $em->getRepository('lpdwSearchEngineBundle:FeatureCategoryValue')->findByFeature($feature);
                try{
                    $featureVal = $em->getRepository('lpdwSearchEngineBundle:FeatureValue')->findOneByFeatureCVandElement($featureCatVal, $element);
                    $id = $featureVal->getFeatureCV()->getId();
                } catch (NoResultException $nr){
                    $id = "";
                }

                $tab = [];
                foreach ($featureCatVal as $key => $value) {
                    $tab[$value->getValue()] = $value->getId();
                }
                $form->add('value' . $i, ChoiceType::class, [
                    'label' => $feature->getName(),
                    'label_attr' => ['class' => 'displayBlock'],
                    'choices' => $tab,
                    'expanded' => false,
                    'multiple' => false,
                    'mapped' => false,
                    'required' => true,
                    'data' => $id
                ]);
            }


            $i++;
        }
        return $form;
    }
}
