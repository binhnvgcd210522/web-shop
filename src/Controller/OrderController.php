<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Order;

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

}
