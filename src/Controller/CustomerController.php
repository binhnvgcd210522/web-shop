<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Customer;
use App\Form\CustomerType;
use Symfony\Component\HttpFoundation\Request;

class CustomerController extends AbstractController
{
    /**
     * @Route("/customer", name="customer_index")
     */
    public function index(): Response
    {
        $em = $this->getDoctrine()->getManager();
        $customer = $em->getRepository(Customer::class)->findAll();
        return $this->render('customer/index.html.twig', [
            'customer' => $customer,
        ]);
    }
    

    /**
    * @Route("/customer/create", name="customer_create", methods={"GET","POST"})
    */
    public function createAction(Request $request)
    {
        $customer = new Customer();
        $form = $this->createForm(CustomerType::class, $customer);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($customer);
            $em->flush();
            return $this->redirectToRoute('customer_show', array('id' => $customer->getId()));
        }
        return $this->render('customer/create.html.twig', array(
            'customer' => $customer,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays the customer with the id given on the url
     *
     * @Route("/customer/{id}", name="customer_show")
     */
    public function showAction(Customer $customer)
    {
        return $this->render('customer/show.html.twig', array(
        'customer' => $customer,
        )); 
    }
}
