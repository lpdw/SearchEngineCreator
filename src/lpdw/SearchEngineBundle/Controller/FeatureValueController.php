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
     * name est le nom d'un element
     * @Route("/{name}/new", name="searchEngine_featureValue_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, $name)
    {
        $em = $this->getDoctrine()->getManager();

        //recupération des élément en fonction du nom en param
        $element = $em->getRepository('lpdwSearchEngineBundle:Element')->findOneByName($name);

        //si le nom en param n'est pas un element valide on redirige vers la liste des element
        if (empty($element)) {
            return $this->redirectToRoute('searchEngine_element_index');
        } else { //sinon
            //on recupere les features de cet element
            $features = $em->getRepository('lpdwSearchEngineBundle:Feature')->findByCategory($element->getCategory());
            //si la categorie de l'element n'a pas de feature on redirige vers la page des feature de la categorie
            if (empty($features)) {
                return $this->redirectToRoute('searchEngine_feature_index', ['name' => $element->getCategory()->getName()]);
            }

            $featureValue = $em->getRepository('lpdwSearchEngineBundle:FeatureValue')->findByElement($element);
            //si l'element possèdes déjà des feature value

            if (!empty($featureValue)) {
                return $this->redirectToRoute('searchEngine_featureValue_edit', ['name' => $name]);
            }
        }
        //var incrément
        $i = 0;
        //nouveau formulaire
        $form = $this->createFormBuilder();
        // boucle de parcourt des features
        $form = $this->get("app.featureValService")->newForm($features,$form);


        //traitement du form
        if ($request->get('form') != NULL) {
            //on parcout les champs du form submit
            foreach ($request->get('form') as $key => $value) {
                //si le champ commande par value il s'agit d'une ligne correcte
                if (substr($key, 0, 5) == "value") {
                    //si le champ un tableau
                    if (is_array($value)) {
                        //parcourt du tableau
                        foreach ($value as $item) {
                            //creation de chaque feature value
                            $featureValue = new FeatureValue();
                            $featureValue->setElement($element);
                            $featureCatVal = $em->getRepository('lpdwSearchEngineBundle:FeatureCategoryValue')->findOneById($item);
                            $featureValue->setFeatureCV($featureCatVal);
                            $em->persist($featureValue);
                            $em->flush($featureValue);
                        }

                    } else { //si le champ n'est pas un tableau
                        $featureValue = new FeatureValue();
                        $featureValue->setElement($element);
                        $featureCatVal = $em->getRepository('lpdwSearchEngineBundle:FeatureCategoryValue')->findOneById($value);
                        $featureValue->setFeatureCV($featureCatVal);
                        $em->persist($featureValue);
                        $em->flush($featureValue);
                    }
                }
            }

            return $this->redirectToRoute('searchEngine_element_index');
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
     * @Route("/{name}/edit", name="searchEngine_featureValue_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, $name)
    {
        $em = $this->getDoctrine()->getManager();

        //recupération des élément en fonction du nom en param
        $element = $em->getRepository('lpdwSearchEngineBundle:Element')->findOneByName($name);

        //si le nom en param n'est pas un element valide on redirige vers la liste des element
        if (empty($element)) {
            return $this->redirectToRoute('searchEngine_element_index');
        } else { //sinon
            //on recupere les features de cet element
            $features = $em->getRepository('lpdwSearchEngineBundle:Feature')->findByCategory($element->getCategory());
            //si la categorie de l'element n'a pas de feature on redirige vers la page des feature de la categorie
            if (empty($features)) {
                return $this->redirectToRoute('searchEngine_feature_index', ['name' => $element->getCategory()->getName()]);
            }
            $featureValue = $em->getRepository('lpdwSearchEngineBundle:FeatureValue')->findByElement($element);

            if (empty($featureValue)) {
                return $this->redirectToRoute('searchEngine_featureValue_new', ['name' => $name]);
            }
        }

        //nouveau formulaire
        $form = $this->createFormBuilder();
        $form = $this->get("app.featureValService")->editForm($features,$form,$element);


//        traitement du form
        if ($request->get('form') != NULL) {
            $featValOld = $em->getRepository('lpdwSearchEngineBundle:FeatureValue')->findByElement($element);
            foreach ($featValOld as $featVal) {
                $em->remove($featVal);
                $em->flush();
            }
            //on parcout les champs du form submit
            foreach ($request->get('form') as $key => $value) {
                //si le champ commande par value il s'agit d'une ligne correcte
                if (substr($key, 0, 5) == "value") {
                    //si le champ un tableau
                    if (is_array($value)) {
                        //parcourt du tableau
                        foreach ($value as $item) {
                            //creation de chaque feature value
                            $featureValue = new FeatureValue();
                            $featureValue->setElement($element);
                            $featureCatVal = $em->getRepository('lpdwSearchEngineBundle:FeatureCategoryValue')->findOneById($item);
                            $featureValue->setFeatureCV($featureCatVal);
                            $em->persist($featureValue);
                            $em->flush($featureValue);
                        }

                    } else { //si le champ n'est pas un tableau
                        $featureValue = new FeatureValue();
                        $featureValue->setElement($element);
                        $featureCatVal = $em->getRepository('lpdwSearchEngineBundle:FeatureCategoryValue')->findOneById($value);
                        $featureValue->setFeatureCV($featureCatVal);
                        $em->persist($featureValue);
                        $em->flush($featureValue);
                    }
                }
            }

            return $this->redirectToRoute('searchEngine_element_index');

        }
        return $this->render('lpdwSearchEngineBundle:featurevalue:edit.html.twig', array(
            'edit_form' => $form->getForm()->createView(),
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
            ->getForm();
    }
}
