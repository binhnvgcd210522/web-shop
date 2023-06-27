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
     * @Route("/customer/{id}", name="customer_show")
     */
    public
    function detailsAction($id)
    {
        $customer = $this->getDoctrine()
            ->getRepository('App\Entity\Customer')
            ->find($id);

        return $this->render('customer/show.html.twig', [
            'customer' => $customer
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
     * @Route("/customer/edit/{id}", name="customer_edit")
     */
    public function editAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $customer = $em->getRepository('App\Entity\Customer')->find($id);
        
        $form = $this->createForm(CustomerType::class, $customer);
        
        if ($this->saveChanges($form, $request, $customer)) {
            $this->addFlash(
                'notice',
                'customer Edited'
            );
            return $this->redirectToRoute('customer_index');
        }
        
        return $this->render('customer/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function saveChanges($form, $request, $customer)
    {
    $form->handleRequest($request);
    
    if ($form->isSubmitted() && $form->isValid()) {
        $customer->setName($request->request->get('customer')['name']);
        $customer->setAddress($request->request->get('customer')['address']);
        $customer->setPhone($request->request->get('customer')['phone']);       
        $em = $this->getDoctrine()->getManager();
        $em->persist($customer);
        $em->flush();
        
        return true;
    }
    return false;
    }

    /**
     * @Route("/customer/delete/{id}", name="customer_delete")
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $customer = $em->getRepository('App\Entity\Customer')->find($id);
        $em->remove($customer);
        $em->flush();
        
        $this->addFlash(
            'error',
            'Customer deleted'
        );
        
        return $this->redirectToRoute('customer_index');

    }

    public function __toString()
    {
        return $this->getName(); // Or any other string representation you prefer
    }
}
