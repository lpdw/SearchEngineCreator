<?php

namespace lpdw\SearchEngineBundle\Controller;

use lpdw\SearchEngineBundle\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Category controller.
 *
 * @Route("searchEngine/category")
 */
class CategoryController extends Controller
{
    /**
     * Lists all category entities.
     *
     * @Route("/", name="searchEngine_category_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $categories = $em->getRepository('lpdwSearchEngineBundle:Category')->findAll();

        return $this->render('lpdwSearchEngineBundle:category:index.html.twig', array(
            'categories' => $categories,
        ));
    }

    /**
     * Creates a new category entity.
     *
     * @Route("/new", name="searchEngine_category_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $category = new Category();
        $form = $this->createForm('lpdw\SearchEngineBundle\Form\CategoryType', $category);
        $form->handleRequest($request);

        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository('lpdwSearchEngineBundle:Category')->lastCat();

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush($category);

            return $this->redirectToRoute('searchEngine_category_index');
        }

        return $this->render('lpdwSearchEngineBundle:category:new.html.twig', array(
            'category' => $category,
            'form' => $form->createView(),
            'lastCat' => $categories
        ));
    }

//    /**
//     * Finds and displays a category entity.
//     *
//     * @Route("/{id}", name="searchEngine_category_show")
//     * @Method("GET")
//     */
//    public function showAction(Category $category)
//    {
//        $deleteForm = $this->createDeleteForm($category);
//
//        return $this->render('lpdwSearchEngineBundle:category:show.html.twig', array(
//            'category' => $category,
//            'delete_form' => $deleteForm->createView(),
//        ));
//    }

    /**
     * Displays a form to edit an existing category entity.
     *
     * @Route("/{id}/edit", name="searchEngine_category_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Category $category)
    {
        $deleteForm = $this->createDeleteForm($category);
        $editForm = $this->createForm('lpdw\SearchEngineBundle\Form\CategoryType', $category);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('searchEngine_category_edit', array('id' => $category->getId()));
        }

        return $this->render('lpdwSearchEngineBundle:category:edit.html.twig', array(
            'category' => $category,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a category entity.
     *
     * @Route("/{id}", name="searchEngine_category_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Category $category)
    {
        $form = $this->createDeleteForm($category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($category);
            $em->flush();
        }

        return $this->redirectToRoute('searchEngine_category_index');
    }

    /**
     * Creates a form to delete a category entity.
     *
     * @param Category $category The category entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Category $category)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('searchEngine_category_delete', array('id' => $category->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
