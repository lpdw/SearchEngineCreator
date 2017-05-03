<?php

namespace lpdw\SearchEngineBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FeatureType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',TextType::class,[
                'label' => 'Name:'
            ])
            ->add('type', ChoiceType::class,[
                'label' => 'Type:',
                'choices'  => array(
                    'select' => 'choix déroulant',
                    'checkbox' => 'checkbox',
                    'radio' => 'radio',
                    'TextType' => 'text',
                    'NumberType' => 'number',
                    'RangeType' => 'range',
                    'BooleanType' => 'boolean',
                ),
            ])
            /*->add('category', EntityType::class, [
                'class' => 'lpdwSearchEngineBundle:Category',
                'choice_label' => 'name',
            ])*/
            ;
    }



    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'lpdw\SearchEngineBundle\Entity\Feature'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'lpdw_searchenginebundle_feature';
    }


}
