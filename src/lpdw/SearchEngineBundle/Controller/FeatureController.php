<?php

namespace lpdw\SearchEngineBundle\Controller;

use lpdw\SearchEngineBundle\Entity\Feature;
use lpdw\SearchEngineBundle\Entity\FeatureCategoryValue;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use lpdw\SearchEngineBundle\Services\insertFCV;

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
     * @Route("/{name}/", name="searchEngine_feature_index")
     * @Method("GET")
     */
    public function indexAction($name)
    {
        $em = $this->getDoctrine()->getManager();

        $category = $em->getRepository('lpdwSearchEngineBundle:Category')->findOneByName($name);
        $features = $em->getRepository('lpdwSearchEngineBundle:Feature')->findByCategory($category);
        return $this->render('lpdwSearchEngineBundle:feature:index.html.twig', array(
            'features' => $features,
            'name' => $name
        ));
    }

    /**
     * Creates a new feature entity.
     *
     * @Route("/{name}/new", name="searchEngine_feature_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, $name)
    {
        $insertFCV = $this->container->get('app.insertfcv');
        $em = $this->getDoctrine()->getManager();
        $feature = new Feature();

//        dump($feature);die;
        $form = $this->createForm('lpdw\SearchEngineBundle\Form\FeatureType', $feature);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $type = $feature->getType();

            $category = $em->getRepository('lpdwSearchEngineBundle:Category')->findOneByName($name);
            if(empty($category)){
                return $this->redirectToRoute('searchEngine_category_index');
            }
            $feature->setCategory($category);

            $em->persist($feature);
            $em->flush($feature);
            $em->refresh($feature);

            //self::insertFCV($request, $feature, $type);
            $insertFCV->insertFCV($request, $feature, $type);

            return $this->redirectToRoute('searchEngine_feature_show', array('id' => $feature->getId(), 'name' => $name));
        }

        return $this->render('lpdwSearchEngineBundle:feature:new.html.twig', array(
            'feature' => $feature,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a feature entity.
     *
     * @Route("/{name}/{id}/", name="searchEngine_feature_show")
     * @Method("GET")
     */
    public function showAction($name, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $feature = $em->getRepository('lpdwSearchEngineBundle:Feature')->findOneById($id);
        $FeatureCategoryValue = $em->getRepository('lpdwSearchEngineBundle:FeatureCategoryValue')->findBy( array('feature' => $id));


        return $this->render('lpdwSearchEngineBundle:feature:show.html.twig', array(
            'feature' => $feature,
            'FeatureCategoryValues' => $FeatureCategoryValue
        ));
    }

    /**
     * Displays a form to edit an existing feature entity.
     *
     * @Route("/{name}/{id}/edit", name="searchEngine_feature_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Feature $feature, $id)
    {
        $insertFCV = $this->container->get('app.insertfcv');

        $deleteForm = $this->createDeleteForm($feature);
        $editForm = $this->createForm('lpdw\SearchEngineBundle\Form\FeatureType', $feature);
        $editForm->handleRequest($request);

        $em = $this->getDoctrine()->getManager();
        $FeatureCategoryValue = $em->getRepository('lpdwSearchEngineBundle:FeatureCategoryValue')->findBy( array('feature' => $id));
        $form = $this->createFormBuilder();

        $checkboxList = [];

        $i=1;

        $type = $feature->getType();

        if($FeatureCategoryValue!=0){
          if($type=="select"){
            foreach ($FeatureCategoryValue as $value){
                $form->add('value'.$i, TextType::class);
                $form->get('value'.$i)->setData($value->getValue());
                $i++;
            }
          }
          if($type=="checkbox"){
            foreach ($FeatureCategoryValue as $value){
                $form->add('value'.$i, TextType::class, ["required" => false, 'attr' => ['id' => 'checkbox_input_'.$id]]);
                $form->get('value'.$i)->setData($value->getValue());
                $form->add('comment'.$i, TextareaType::class, ["required" => false, 'attr' => ['id' => 'checkbox_comment_'.$id]]);
                $form->get('comment'.$i)->setData($value->getComment());
                $form->add('image'.$i, FileType::class, ["required" => false]);
                if($value->getImage()) {
                    $form->get('image'.$i)->setData(new File($this->container->getParameter('kernel.root_dir') . '/../web/uploads/images/' . $value->getImage()));
                }

                array_push($checkboxList, [$form->get('value'.$i), $form->get('comment'.$i), $form->get('image'.$i)]);
                $i++;
            }
          }
          if($type=="radio"){
            foreach ($FeatureCategoryValue as $value){
                $form->add('value'.$i, TextType::class);
                $form->get('value'.$i)->setData($value->getValue());
                $i++;
            }
          }
          if($type=="TextType"){
            foreach ($FeatureCategoryValue as $value){
                $form->add('value'.$i, TextType::class);
                $form->get('value'.$i)->setData($value->getValue());
            }
          }
          if($type=="NumberType"){
            foreach ($FeatureCategoryValue as $value){
                $form->add('value'.$i, NumberType::class);
                $form->get('value'.$i)->setData(intval($value->getValue()));
            }
          }
          if($type=="RangeType"){
            foreach ($FeatureCategoryValue as $value){
              $pieces = explode("-", $value->getValue());
              $form->add('min', TextType::class);
              $form->get('min')->setData($pieces[0]);
              $form->add('max', TextType::class);
              $form->get('max')->setData($pieces[1]);
            }
          }
          if($type=="BooleanType"){
            foreach ($FeatureCategoryValue as $value){
                $form->add('value'.$i, TextType::class);
                $form->get('value'.$i)->setData($value->getValue());
            }
          }
        }
        $send_form = $form->getForm();

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $this->getDoctrine()->getManager()->flush();

            //self::insertFCV($request, $feature, $type, $form);
            $insertFCV->insertFCV($request, $feature, $type, $form);

            foreach ($FeatureCategoryValue as $value){
              $em->remove($value);
              $em->flush();
            }

            return $this->redirectToRoute('searchEngine_feature_show', array('id' => $feature->getId()));
        }

        return $this->render('lpdwSearchEngineBundle:feature:edit.html.twig', array(
            'checkboxList' => $checkboxList,
            'feature' => $feature,
            'form' => $send_form->createView(),
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

    /*function insertFCV($request, $feature, $type, $form=null){
      $em = $this->getDoctrine()->getManager();

      if($request->request->get('lpdw_searchenginebundle_feature')['type'] == "checkbox"){
        //EDIT

        if($request->request->get('form')){
          //GENERATE SYMFONY
          $taille = ceil((count($request->request->get('form'))-1)/2);
          for($i=1; $i<=$taille; $i++){
            $FCV = new FeatureCategoryValue();
            $FCV->setValue($request->request->get('form')["value".$i]);
            $FCV->setFeature($feature);
            $FCV->setComment($request->request->get('form')["comment".$i]);

            $file = $request->files->get('form')['image'.$i];

            if($file) {
                $fileName = md5(uniqid()).'.'.$file->guessExtension();

                $file->move(
                    $this->container->getParameter('kernel.root_dir') . '/../web/uploads/images',
                    $fileName
                );

                $FCV->setImage($fileName);

                if($form->get('image'.$i)->getData()) {
                    unlink($this->container->getParameter('kernel.root_dir') . '/../web/uploads/images/'.$form->get('image'.$i)->getData()->getFileName());
                }

            } else if($form->get('image'.$i)->getData()) {
                $file = $form->get('image'.$i)->getData();

                $FCV->setImage($file->getFileName());
            }

            $em->persist($FCV);
            $em->flush($FCV);
          }


          //GENERATE JS
          for($i=1; $i<=(count($request->request)); $i++){
            if($request->request->get('input_checkbox_'.$i)){
              $FCV = new FeatureCategoryValue();
              $FCV->setValue($request->request->get('input_checkbox_'.$i));
              $FCV->setFeature($feature);
              $FCV->setComment($request->request->get('comment_checkbox_'.$i));

              $file = $request->files->get('image_checkbox_'.$i);

              if($file) {
                  $fileName = md5(uniqid()).'.'.$file->guessExtension();

                  $file->move(
                      $this->container->getParameter('kernel.root_dir') . '/../web/uploads/images',
                      $fileName
                  );

                  $FCV->setImage($fileName);
              }

              $em->persist($FCV);
              $em->flush($FCV);
            }
          }

        }
        else{
          //news

          $taille = ceil((count($request->request)-1)/2);
          for($i=1; $i<=$taille; $i++){
            $FCV = new FeatureCategoryValue();
            $FCV->setValue($request->request->get('input_checkbox_'.$i));
            $FCV->setFeature($feature);
            $FCV->setComment($request->request->get('comment_checkbox_'.$i));

            $file = $request->files->get('image_checkbox_'.$i);

            if($file) {
                $fileName = md5(uniqid()).'.'.$file->guessExtension();

                $file->move(
                    $this->container->getParameter('kernel.root_dir') . '/../web/uploads/images',
                    $fileName
                );

                $FCV->setImage($fileName);
            }

            $em->persist($FCV);
            $em->flush($FCV);
          }
        }
      }
      elseif ($request->request->get('lpdw_searchenginebundle_feature')['type'] == "RangeType") {

        if($request->request->get('form')){
          $FCV = new FeatureCategoryValue();
          $FCV->setValue($request->request->get('form')["min"]."-".$request->request->get('form')["max"]);
          $FCV->setFeature($feature);
          $em->persist($FCV);
          $em->flush($FCV);
        }
        else{
          $FCV = new FeatureCategoryValue();
          $FCV->setValue($request->request->get('input_min')."-".$request->request->get('input_max'));
          $FCV->setFeature($feature);
          $em->persist($FCV);
          $em->flush($FCV);
        }
      }
      else{

        if($request->request->get('form')){
          dump($request->request->get('form'));die;

          for($i=1; $i<(count($request->request)); $i++){
            if($request->request->get('form')['value'.$i]){
              $FCV = new FeatureCategoryValue();
              $FCV->setValue($request->request->get('form')['value'.$i]);
              $FCV->setFeature($feature);
              $em->persist($FCV);
              $em->flush($FCV);
            }
          }
          for($i=1; $i<=count($request->request); $i++){
            if($request->request->get('input_select_'.$i)){
              $FCV = new FeatureCategoryValue();
              $FCV->setValue($request->request->get('input_select_'.$i));
              $FCV->setFeature($feature);
              $em->persist($FCV);
              $em->flush($FCV);
            }
          }
        }
        else{
          foreach ($request->request as $key => $value) {

            if (strstr($key, 'input')) {
                $FCV = new FeatureCategoryValue();
                $FCV->setValue($value);
                $FCV->setFeature($feature);
                $em->persist($FCV);
                $em->flush($FCV);
            }
          }
        }
      }
    }*/
}
