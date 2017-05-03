<?php

namespace lpdw\SearchEngineBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FeatureValueType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('value')
            ->add('elements', EntityType::class, [
                'class' => 'lpdwSearchEngineBundle:Element',
                'choice_label' => 'name',
            ])
            ->add('features', EntityType::class, [
                'class' => 'lpdwSearchEngineBundle:Feature',
                'choice_label' => 'name',
            ]);

    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'lpdw\SearchEngineBundle\Entity\FeatureValue'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'lpdw_searchenginebundle_featurevalue';
    }


}
