<?php

namespace lpdw\SearchEngineBundle\Controller;

use lpdw\SearchEngineBundle\Entity\FeatureValue;
use lpdw\SearchEngineBundle\Form\FeatureValueType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\BooleanType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


/**
 * Featurevalue controller.
 *
 * @Route("searchEngine/featureValue")
 */
class FeatureValueController extends Controller
{
    /**
     * Lists all featureValue entities.
     *
     * @Route("/", name="searchEngine_featureValue_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $featureValues = $em->getRepository('lpdwSearchEngineBundle:FeatureValue')->findAll();

        return $this->render('lpdwSearchEngineBundle:featurevalue:index.html.twig', array(
            'featureValues' => $featureValues,
        ));
    }

    /**
     * Creates a new featureValue entity.
     *
     * @Route("/{name}/new", name="searchEngine_featureValue_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, $name)
    {
        $em = $this->getDoctrine()->getManager();
        $featureValue = new Featurevalue();

        //recupération des élément en fonction du nom en param
        $element =  $em->getRepository('lpdwSearchEngineBundle:Element')->findOneByName($name);

        //si le nom en param n'est pas un element valide on redirige vers la liste des element
        if(empty($element)){
            return $this->redirectToRoute('searchEngine_element_index');
        }else { //sinon
            //on recupere les features de cet element
            $features = $em->getRepository('lpdwSearchEngineBundle:Feature')->findByCategory($element->getCategory());
            //si la categorie de l'element n'a pas de feature on redirige vers la page des feature de la categorie
            if(empty($features)){
                return $this->redirectToRoute('searchEngine_feature_index', [ 'name' => $element->getCategory()->getName() ]);
            }
        }
        //var incrément
        $i = 0;
        //nouveau formulaire
        $form = $this->createFormBuilder();
            // boucle de parcourt des features
        foreach ($features as $feature){

                if($feature->getType() == 'TextType' ){
                    $featureCatVal = $em->getRepository('lpdwSearchEngineBundle:FeatureCategoryValue')->findOneByFeature($feature);
                    $form->add('value'.$i, TextType::class,[
                    'label'=> $feature->getName(), 'mapped' => false, [ 'attr' => ['class' => $featureCatVal->getId()]]
                    ]);
                }
                if($feature->getType() == 'NumberType'){
                    $featureCatVal = $em->getRepository('lpdwSearchEngineBundle:FeatureCategoryValue')->findOneByFeature($feature);

                    $form->add('value'.$i, NumberType::class,[
                    'label'=> $feature->getName(), 'mapped' => false, [ 'attr' => ['class' => $featureCatVal->getId()]]
                    ]);
                }
                if($feature->getType() == 'BooleanType'){
                    $featureCatVal = $em->getRepository('lpdwSearchEngineBundle:FeatureCategoryValue')->findOneByFeature($feature);

                    $originFeature = $featureCatVal[0]->getFeature();
                    $form->add('value'.$i, [
                    'label'=> $feature->getName(), 'mapped' => false, [ 'attr' => ['class' => $featureCatVal->getId()]]
                    ]);
                }
                if($feature->getType() == 'RangeType'){
                    $featureCatVal = $em->getRepository('lpdwSearchEngineBundle:FeatureCategoryValue')->findOneByFeature($feature);

                    $values = explode("-", $featureCatVal->getValue());
                    $form->add('value'.$i, NumberType::class,[
                    'label'=> $feature->getName(),
                    'attr' =>['min' => $values[0],
                    'max' => $values[1]], 'mapped' => false, [ 'attr' => ['class' => $featureCatVal->getId()]]
                    ]);
                }
                if($feature->getType() == 'checkbox'){
                    $featureCatVal = $em->getRepository('lpdwSearchEngineBundle:FeatureCategoryValue')->findByFeature($feature);

                    $tab = [];
                    foreach ($featureCatVal as $key => $value) {
                        $tab[$value->getValue()] = $value->getId();
                    }
                    $form->add('value'.$i, ChoiceType::class,[
                    'label'=> $feature->getName(),
                    'choices' => $tab,
                    'expanded' => true,
                    'multiple' => true,
                    'mapped' => false,
                    ]);
                }

                if($feature->getType() == 'radio'){
                    $featureCatVal = $em->getRepository('lpdwSearchEngineBundle:FeatureCategoryValue')->findByFeature($feature);
                    $tab = [];
                    foreach ($featureCatVal as $key => $value) {
                        $tab[$value->getValue()] = $value->getId();
                    }
                    $form->add('value'.$i, ChoiceType::class,[
                        'label'=> $feature->getName(),
                        'choices' => $tab,
                        'expanded' => true,
                        'multiple' => true,
                        'mapped' => false,
                    ]);
                }

                if($feature->getType() == 'select'){
                    $featureCatVal = $em->getRepository('lpdwSearchEngineBundle:FeatureCategoryValue')->findByFeature($feature);
                    $tab = [];
                    foreach ($featureCatVal as $key => $value) {
                        $tab[$value->getValue()] = $value->getId();
                    }
                    $form->add('value'.$i, ChoiceType::class,[
                        'label'=> $feature->getName(),
                        'choices' => $tab,
                        'expanded' => false,
                        'multiple' => false,
                        'mapped' => false,
                    ]);
                }


                $i++;
            }



        if(empty($element)){
            return $this->redirectToRoute('searchEngine_element_index');
        }


        if($request->get('form') != NULL){
            foreach ($request->get('form') as $key => $value){
                if(substr($key,0,5) == "value"){
                    if(is_array($value)){
                        foreach ($value as $item){
                            $featureValue = new FeatureValue();
                            $featureValue->setElement($element);
                            $featureCatVal = $em->getRepository('lpdwSearchEngineBundle:FeatureCategoryValue')->findOneById($item);

                            $featureValue->setFeatureCV($featureCatVal);
                            $em->persist($featureValue);
                            $em->flush($featureValue);
                        }

                    }
                    else {
                        $featureValue = new FeatureValue();
                        $featureValue->setElement($element);
                        $featureCatVal = $em->getRepository('lpdwSearchEngineBundle:FeatureCategoryValue')->findOneById($value);
                        $featureValue->setFeatureCV($featureCatVal);
                        $em->persist($featureValue);
                        $em->flush($featureValue);
                    }
                }
            }

        }

        return $this->render('lpdwSearchEngineBundle:featurevalue:new.html.twig', array(
                'form' => $form->getForm()->createView(),
        ));
    }

    /**
     * Finds and displays a featureValue entity.
     *
     * @Route("/{id}", name="searchEngine_featureValue_show")
     * @Method("GET")
     */
    public function showAction(FeatureValue $featureValue)
    {
        $deleteForm = $this->createDeleteForm($featureValue);

        return $this->render('lpdwSearchEngineBundle:featurevalue:show.html.twig', array(
            'featureValue' => $featureValue,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing featureValue entity.
     *
     * @Route("/{id}/edit", name="searchEngine_featureValue_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, FeatureValue $featureValue)
    {
        $deleteForm = $this->createDeleteForm($featureValue);
        $editForm = $this->createForm('lpdw\SearchEngineBundle\Form\FeatureValueType', $featureValue);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('searchEngine_featureValue_edit', array('id' => $featureValue->getId()));
        }

        return $this->render('lpdwSearchEngineBundle:featurevalue:edit.html.twig', array(
            'featureValue' => $featureValue,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a featureValue entity.
     *
     * @Route("/{id}", name="searchEngine_featureValue_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, FeatureValue $featureValue)
    {
        $form = $this->createDeleteForm($featureValue);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($featureValue);
            $em->flush();
        }

        return $this->redirectToRoute('searchEngine_featureValue_index');
    }

    /**
     * Creates a form to delete a featureValue entity.
     *
     * @param FeatureValue $featureValue The featureValue entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(FeatureValue $featureValue)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('searchEngine_featureValue_delete', array('id' => $featureValue->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
