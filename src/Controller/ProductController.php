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
 * @Route("/product", name="product_index")
 */
public function listAction()
{
    $product = $this->getDoctrine()
        ->getRepository('App\Entity\Product')
        ->findAll();
    return $this->render('product/index.html.twig', [
        'product' => $product
    ]);
}


 /**
 * @Route("/product/{id}", name="product_show")
 */
public
function showAction($id)
{
    $product = $this->getDoctrine()
        ->getRepository('App\Entity\Product')
        ->find($id);

    return $this->render('product/show.html.twig', [
        'product' => $product
    ]);
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

            return $this->redirectToRoute('product_show', array('id' => $product->getId()));
        }

        return $this->render('product/new.html.twig', [
            'products' => $products,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/product/delete/{id}", name="product_delete")
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository('App\Entity\Product')->find($id);
        $em->remove($product);
        $em->flush();
        
        $this->addFlash(
            'error',
            'Product deleted'
        );
        
        return $this->redirectToRoute('product_index');

    }
}