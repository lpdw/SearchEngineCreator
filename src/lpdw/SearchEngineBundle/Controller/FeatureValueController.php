<?php

namespace lpdw\SearchEngineBundle\Controller;

use lpdw\SearchEngineBundle\Entity\FeatureValue;
use lpdw\SearchEngineBundle\Form\FeatureValueType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
        $element =  $em->getRepository('lpdwSearchEngineBundle:Element')->findOneByName($name);
        $features = $em->getRepository('lpdwSearchEngineBundle:Feature')->findByCategory($element->getCategory());


        $i = 0;
        $form = $this->createFormBuilder();

            foreach ($features as $feature){
                dump($feature);die;
                $form->add('value'.$i, TextType::class,[
                    'label'=> $feature->getName(),

            ]);

                $i++;
            }
        

//        die;


        if(empty($element)){
            return $this->redirectToRoute('searchEngine_element_index');
        }
        // $form = $this->createForm('lpdw\SearchEngineBundle\Form\FeatureValueType', $featureValue);
        // $form->handleRequest($request);

        // if ($form->isSubmitted() && $form->isValid()) {

        //     $featureValue->setElement($element[0]);
        //     $em->persist($featureValue);
        //     $em->flush($featureValue);

        //     return $this->redirectToRoute('searchEngine_featureValue_show', array('id' => $featureValue->getId()));
        // }

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
