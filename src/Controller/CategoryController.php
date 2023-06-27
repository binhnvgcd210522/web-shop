<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\CategoryType;
use App\Entity\Category;
use Symfony\Component\HttpFoundation\Request;

class CategoryController extends AbstractController
{
    /**
     * @Route("/category", name="category_index")
     */    
    public function index(): Response
    {
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository(Category::class)->findAll();
        return $this->render('category/index.html.twig', [
            'category' => $category,
        ]);
    } 

    /**
     * Finds and displays the category with the id given on the url
     *
     * @Route("/category/{id}", name="category_show")
     */
    public function showAction($id)
    {
        $category = $this->getDoctrine()
        ->getRepository('App\Entity\Category')
        ->find($id);
        return $this->render('category/show.html.twig', array(
        'category' => $category,
        )); 
    }
    

    /**
    * @Route("/category/create", name="category_new", methods={"GET","POST"})
    */
    public function createAction(Request $request)
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();
            return $this->redirectToRoute('category_show', array('id' => $category->getId()));
        }
        return $this->render('category/new.html.twig', array(
            'category' => $category,
            'form' => $form->createView(),
        ));
    }
}
