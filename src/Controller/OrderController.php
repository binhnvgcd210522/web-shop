<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Order;
use Symfony\Component\HttpFoundation\Request;
use App\Form\OrderType;

class OrderController extends AbstractController
{
    /**
     * @Route("/order", name="order_index")
     */    
    public function index(): Response
    {
        $em = $this->getDoctrine()->getManager();
        $order = $em->getRepository(Order::class)->findAll();
        return $this->render('order/index.html.twig', [
            'order' => $order,
        ]);
    } 

     /**
     * @Route("/order/create", name="order_create", methods={"GET","POST"})
     */
    public function createAction(Request $request)
    {
        $order = new Order();
        $form = $this->createForm(OrderType::class, $order);
        
        if ($this->saveChanges($form, $request, $order)) {
            $this->addFlash(
                'notice',
                'Order Added'
            );
            
            return $this->redirectToRoute('order_index');
        }
        
        return $this->render('order/create.html.twig', [
            'form' => $form->createView()
        ]);
    }
    public function saveChanges($form, $request, $order)
    {
    $form->handleRequest($request);
    
    if ($form->isSubmitted() && $form->isValid()) {
        $order->setName($request->request->get('order')['customer']);
        $order->setAddress($request->request->get('order')['date']);    
        $em = $this->getDoctrine()->getManager();
        $em->persist($order);
        $em->flush();
        
        return true;
    }
    return false;
    }

}
