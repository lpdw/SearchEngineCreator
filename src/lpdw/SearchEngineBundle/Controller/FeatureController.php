<?php

namespace lpdw\SearchEngineBundle\Controller;

use lpdw\SearchEngineBundle\Entity\Feature;
use lpdw\SearchEngineBundle\Entity\FeatureCategoryValue;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Feature controller.
 *
 * @Route("searchEngine/feature")
 */
class FeatureController extends Controller
{
    /**
     * Lists all feature entities.
     *
     * @Route("/category/{id}/", name="searchEngine_feature_index")
     * @Method("GET")
     */
    public function indexAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        //$features = $em->getRepository('lpdwSearchEngineBundle:Feature')->findAll();
        $features = $em->getRepository('lpdwSearchEngineBundle:Feature')->findBy( array('category' => $id));

        return $this->render('lpdwSearchEngineBundle:feature:index.html.twig', array(
            'features' => $features,
            'id' => $id,
        ));
    }

    /**
     * Creates a new feature entity.
     *
     * @Route("/{id}/new", name="searchEngine_feature_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $feature = new Feature();

        $form = $this->createForm('lpdw\SearchEngineBundle\Form\FeatureType', $feature);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $type = $feature->getType();

            $category = $em->getRepository('lpdwSearchEngineBundle:Category')->findOneById($id);
            $feature->setCategory($category);

            $em->persist($feature);
            $em->flush($feature);
            $em->refresh($feature);

            self::insertFCV($request->request, $feature, $type);

            return $this->redirectToRoute('searchEngine_feature_show', array('id' => $feature->getId()));
        }

        return $this->render('lpdwSearchEngineBundle:feature:new.html.twig', array(
            'feature' => $feature,
            'form' => $form->createView(),
            'id' => $id,
        ));
    }

    /**
     * Finds and displays a feature entity.
     *
     * @Route("/{id}/", name="searchEngine_feature_show")
     * @Method("GET")
     */
    public function showAction(Feature $feature)
    {
        $deleteForm = $this->createDeleteForm($feature);

        return $this->render('lpdwSearchEngineBundle:feature:show.html.twig', array(
            'feature' => $feature,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing feature entity.
     *
     * @Route("/{id}/edit", name="searchEngine_feature_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Feature $feature)
    {
        $deleteForm = $this->createDeleteForm($feature);
        $editForm = $this->createForm('lpdw\SearchEngineBundle\Form\FeatureType', $feature);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('searchEngine_feature_edit', array('id' => $feature->getId()));
        }

        return $this->render('lpdwSearchEngineBundle:feature:edit.html.twig', array(
            'feature' => $feature,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a feature entity.
     *
     * @Route("/{id}", name="searchEngine_feature_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Feature $feature)
    {

        $id= $feature->getCategory();

        $form = $this->createDeleteForm($feature);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($feature);
            $em->flush();
        }

        return $this->redirectToRoute('searchEngine_feature_index', array('id' => $id->getId()));
    }

    /**
     * Creates a form to delete a feature entity.
     *
     * @param Feature $feature The feature entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Feature $feature)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('searchEngine_feature_delete', array('id' => $feature->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    function insertFCV($request, $feature, $type){
      $em = $this->getDoctrine()->getManager();

      foreach ($request as $key => $value) {

        /*if (strstr($key, 'input')) {
            $FCV = new FeatureCategoryValue();
            $FCV->setValue($value);
            $FCV->setFeature($feature);
            if($type="checkbox"){
              //$FCV->setImage();
            }
            elseif ($type="range") {
              //$FCV->setImage();
            }
            $em->persist($FCV);
            $em->flush($FCV);
        }*/
      }
//      dump($request);die;
        if($request->get('lpdw_searchenginebundle_feature')['type'] == "checkbox"){
            $taille = (count($request)-1)/3;
            for($i=1; $i<count($request); $i++){
                dump($request);
                dump($request->get('input_checkbox_'.$i));
                dump($request->get('comment_checkbox_'.$i));
                dump($request->get('image_checkbox_'.$i));
            }
        }



      die();
    }
}
