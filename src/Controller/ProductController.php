<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Product;
use App\Form\ProductType;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

class ProductController extends AbstractController
{
    /**
   * Lists all product entities.
   *
   * @Route("/product", methods={"GET", "POST"},name="product_index")
   */
    public function indexAction()
    {
    $em = $this->getDoctrine()->getManager();

    $products = $em->getRepository(Product::class)->findAll();
    for ($i=0;$i<sizeof($products);$i++){
      $formViews[$i]= $this->createDeleteForm($products[$i])->createView();
    }
    // $formViews là 1 mảng gồm các formView đơn lẻ
    return $this->render('product/index.html.twig', array(
      'products' => $products, 'delete_forms' => $formViews,
    ));  
    }

    /**
     * Finds and displays the product with the id given on the url
     *
     * @Route("/product/{id}", name="product_show")
     */
    public function showAction(Product $product)
    {
        return $this->render('product/show.html.twig', array(
        'product' => $product,
        )); 
    }

    /**
    * Displays a form to edit an existing product entity.
    *
    * @Route("/product/{id}/edit", methods={"GET","POST"},name="product_edit")
    */
    public function editAction(Request $request, Product $product)
    {
        $deleteForm = $this->createDeleteForm($product);
        $editForm = $this->createForm(ProductType::class, $product);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('product_edit', array('id' => $product->getId()));
        }

        return $this->render('product/edit.html.twig', array(
        'product' => $product,
        'edit_form' => $editForm->createView(),
        'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * @Route("/product/new", name="product_new")
     */
    public function addAction(Request $request): Response
    {        
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            return $this->redirectToRoute('product_index');
        }

        return $this->render('product/new.html.twig', [
            'products' => $products,
            'form' => $form->createView(),
        ]);
    }

    /**
    * Delete a product entity.    
    * @Route("/product/{id}", methods={"DELETE"},name="product_delete")
    */
    public function deleteAction(Request $request, Product $product)
    {
        $form = $this->createDeleteForm($product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
        $em = $this->getDoctrine()->getManager();
        $em->remove($product);
        $em->flush();
        }

        return $this->redirectToRoute('product_index');
    }
    
    /**
    * Creates a form to delete a product entity.
    *
    * @param Product $product The product entity
    *
    * @return Form The form
    */
    private function createDeleteForm(Product $product): Form
    {
        return $this->createFormBuilder()
        ->setAction($this->generateUrl('product_delete', array('id' => $product->getId())))
        ->setMethod('DELETE')
        ->getForm()
        ;
    }
}
