<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Entity\Car;
use App\Form\CustomerType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends AbstractController
{
  
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/', name: 'app_home')]
    public function new(Request $request, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();

        $customer = new Customer();
        $form = $this->createForm(CustomerType::class, $customer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $customer = $form->getData();
            $entityManager->persist($customer);
            
            foreach($customer->getCars() as $car)
            {
                $car->setCustomer($customer);
            }

            $entityManager->flush();
            return new Response('Saved new product with id '.$customer->getId());
        }

        return $this->renderForm('home/index.html.twig', [
            'form' => $form,
        ]);
    }
}
