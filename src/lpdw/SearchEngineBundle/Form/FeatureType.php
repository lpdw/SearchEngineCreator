<?php

namespace lpdw\SearchEngineBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class FeatureType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',TextType::class,[
                'label' => false,
                'attr' => array(
                    'placeholder'   => 'Nom de la caractéristique',
                    'class'         => 'fontClemente'
                )
            ])
            ->add('type', ChoiceType::class,[
                'label' => 'Type:',
                'choices'  => array(
                    'select' => 'select',
                    'checkbox' => 'checkbox',
                    'radio' => 'radio',
                    'Range' => 'RangeType',
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
