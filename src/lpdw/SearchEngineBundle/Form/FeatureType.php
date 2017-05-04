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
            ->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
                $form = $event->getForm();
                dump($event);die;

                if($event->getData()["type"] == "checkbox") {
                    $form->add('image', FileType::class, ['mapped' => false]);
                }

                // Check whether the user has chosen to display his email or not.
                // If the data was submitted previously, the additional value that is
                // included in the request variables needs to be removed.
                /*if (true === $user['show_email']) {
                    $form->add('email', EmailType::class);
                } else {
                    unset($user['email']);
                    $event->setData($user);
                }*/
            })
            ->getForm();
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
