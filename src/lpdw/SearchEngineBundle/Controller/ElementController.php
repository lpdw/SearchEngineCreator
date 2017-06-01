<?php

namespace lpdw\SearchEngineBundle\Controller;

use lpdw\SearchEngineBundle\Entity\Category;
use lpdw\SearchEngineBundle\Entity\Element;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\File;

/**
 * Element controller.
 *
 * @Route("searchEngine/element")
 */
class ElementController extends Controller
{
    /**
     * Lists all element entities.
     * name = category
     * @Route("/{category_name}", name="searchEngine_element_index")
     * @Method("GET")
     */
    public function indexAction($category_name)
    {
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository('lpdwSearchEngineBundle:Category')->findByName($category_name);
        if(empty($category)){
            return $this->redirectToRoute('searchEngine_category_index');
        }
        $elements = $em->getRepository('lpdwSearchEngineBundle:Element')->findByCategory($category);

        return $this->render('lpdwSearchEngineBundle:element:index.html.twig', array(
            'elements' => $elements,
        ));
    }

    /**
     * Creates a new element entity.
     * name = category
     * @Route("/{category_name}/new", name="searchEngine_element_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request,$category_name)
    {
        $em = $this->getDoctrine()->getManager();

        $element = new Element();

        $category =  $em->getRepository('lpdwSearchEngineBundle:Category')->findOneByName($category_name);



        if(empty($category)){

            return $this->redirectToRoute('searchEngine_category_index');
        }
        $form = $this->createForm('lpdw\SearchEngineBundle\Form\ElementType', $element);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $element->setCategory($category);
            if($element->getImage())
            {
                $file = $element->getImage();
                $fileName = md5(uniqid()).'.'.$file->guessExtension();

                $file->move(
                    $this->getParameter('images_directory'),
                    $fileName
                );

                $element->setImage($fileName);
            }

            $element->setCategory($category);
            $em->persist($element);

            $em->flush($element);

            return $this->redirectToRoute('searchEngine_element_index', ['category_name' => $category->getName()]);
        }

        return $this->render('lpdwSearchEngineBundle:element:new.html.twig', array(
            'category' => $category,
            'element' => $element,
            'form' => $form->createView(),
        ));
    }

//    /**
//     * Finds and displays a element entity.
//     *
//     * @Route("/{id}", name="searchEngine_element_show")
//     * @Method("GET")
//     */
//    public function showAction(Element $element)
//    {
//        $deleteForm = $this->createDeleteForm($element);
//
//        return $this->render('lpdwSearchEngineBundle:element:show.html.twig', array(
//            'element' => $element,
//            'delete_form' => $deleteForm->createView(),
//        ));
//    }

    /**
     * Displays a form to edit an existing element entity.
     *
     * @Route("/{category_name}/{element_name}/edit", name="searchEngine_element_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, $category_name,$element_name)
    {

        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository('lpdwSearchEngineBundle:Category')->findByName($category_name);

        $element = $em->getRepository('lpdwSearchEngineBundle:Element')->findOneByCategoryAndName($category,$element_name);

        $oldImageName = $element->getImage();

        if($element->getImage()) {
            //Transform the string filename in a file object for the FileType field
            $element->setImage(
                new File($this->getParameter('images_directory').'/'.$element->getImage())
            );
        }

        $deleteForm = $this->createDeleteForm($element);
        $editForm = $this->createForm('lpdw\SearchEngineBundle\Form\ElementType', $element);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            if($element->getImage()) {
                if($oldImageName)
                    unlink($this->getParameter('images_directory').'/'.$oldImageName);

                $file = $element->getImage();
                $fileName = md5(uniqid()).'.'.$file->guessExtension();

                $file->move(
                    $this->getParameter('images_directory'),
                    $fileName
                );

                $element->setImage($fileName);
            }
            //else we keep the old image
            else {
                $element->setImage($oldImageName);
            }

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('searchEngine_element_edit', ['category_name' => $category->getName()]);
        }

        return $this->render('lpdwSearchEngineBundle:element:edit.html.twig', array(
            'element' => $element,
            'oldImageName' => $oldImageName,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a element entity.
     *
     * @Route("/{category_name}/{element_name}", name="searchEngine_element_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request,$category_name,$element_name)
    {

        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository('lpdwSearchEngineBundle:Category')->findByName($category_name);
        $element = $em->getRepository('lpdwSearchEngineBundle:Element')->findOneByCategoryAndName($category,$element_name);
        $form = $this->createDeleteForm($element);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if($element->getImage())
            {
                unlink($this->getParameter('images_directory').'/'.$element->getImage());
            }
            $em = $this->getDoctrine()->getManager();
            $em->remove($element);
            $em->flush();
        }

        return $this->redirectToRoute('searchEngine_element_index', ['category_name' => $element->getCategory()->getName()]);
    }

    /**
     * Creates a form to delete a element entity.
     *
     * @param Element $element The element entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Element $element)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('searchEngine_element_delete', array('id' => $element->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
