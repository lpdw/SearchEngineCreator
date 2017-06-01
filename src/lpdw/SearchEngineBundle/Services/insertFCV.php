<?php
namespace lpdw\SearchEngineBundle\Services;
use Doctrine\ORM\EntityManager;
use AppBundle\Entity\Article;
use AppBundle\Form\ArticleType;
use lpdw\SearchEngineBundle\Entity\Feature;
use lpdw\SearchEngineBundle\Entity\FeatureCategoryValue;

class insertFCV
{
  private $doctrine;
  public function __construct($doctrine)
  {
      $this->doctrine = $doctrine;
  }

  public function insertFCV($request, $feature, $type, $form=null){
    //$em = $this->getDoctrine()->getManager();
    $em = $this->doctrine;

    if($request->request->get('lpdw_searchenginebundle_feature')['type'] == "checkbox"){
      //EDIT

      if($request->request->get('form')){
        //GENERATE SYMFONY
        $taille = ceil((count($request->request->get('form'))-1)/2);
        for($i=1; $i<=$taille; $i++){
          if($request->request->get('form')["value".$i]){
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
       for($i=1; $i<(count($request->request->get('form'))); $i++){
          if($request->request->get('form')['value'.$i]){
            $FCV = new FeatureCategoryValue();
            $FCV->setValue($request->request->get('form')['value'.$i]);
            $FCV->setFeature($feature);
            $em->persist($FCV);
            $em->flush($FCV);
          }
        }

        $count=count($request->request->get('form'))-1;
        for($i=3; $i<=count($request->request); $i++){
          $count++;
          $name_request = 'input_select_'.$count;
          if($request->request->get($name_request)){
            $FCV = new FeatureCategoryValue();
            $FCV->setValue($request->request->get($name_request));
            $FCV->setFeature($feature);
            $em->persist($FCV);
            $em->flush($FCV);
          }
        }

      }
      else{
        foreach ($request->request as $key => $value) {
          if (strstr($key, 'input')) {
            if($value){
              $FCV = new FeatureCategoryValue();
              $FCV->setValue($value);
              $FCV->setFeature($feature);
              $em->persist($FCV);
              $em->flush($FCV);
            }
          }
        }
      }
    }
  }
}
