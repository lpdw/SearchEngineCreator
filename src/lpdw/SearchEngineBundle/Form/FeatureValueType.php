<?php

namespace lpdw\SearchEngineBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

class FeatureValueType extends AbstractType
{

//    private $id;
//    function __construct($id)
//    {
//        $this->id= $id;
//    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
//        dump($options);die;
//        $this->id=$options['data'];
//        dump($this->id);
//        die;
//        dump($id);die;
        $builder
            ->add('value')
            ->add('features', EntityType::class, [
                'class' => 'lpdwSearchEngineBundle:Feature',
                'query_builder' => function (EntityRepository $e) {
                    return $e->createQueryBuilder('u')
                        ->where('u.category = :id')
                        ->setParameter('id', 2);
                },
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
