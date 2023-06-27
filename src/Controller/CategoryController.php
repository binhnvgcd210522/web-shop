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
   *
   * @Route("/category/{id}", name="category_show")
   */
    public function showAction(Category $category)
    {
        return $this->render('category/show.html.twig', array(
        'category' => $category,
        ));
    }

    /**
    * @Route("/category/new", name="category_new")
    */
    public function addAction(Request $request): Response
    {        
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute('category_index', ['id' => $category->getId()]);
        }

        return $this->render('category/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }    
}
