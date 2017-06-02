<?php

namespace lpdw\SearchEngineBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ElementType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',TextType::class, [
                'label' => false,
                'attr' => array(
                    'placeholder'   => 'Nom de l\'élément',
                    'class'         => 'fontClemente'
                )])
            ->add('comment', TextareaType::class, [
                "required" => false,
                "label" => false,
                'attr' => array(
                "placeholder" => "Commentaire",
                'class' => 'fontClemente'
            )])
            ->add('image', FileType::class, array(
                'required' => false,
                'label' => 'Ajouter une photo (png, jpg)',
                'label_attr' => array(
                'class' => 'labelUpload'
            )));
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'lpdw\SearchEngineBundle\Entity\Element'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'lpdw_searchenginebundle_element';
    }


}
