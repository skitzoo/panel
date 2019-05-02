<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Form\CustomerEditType;
use App\Repository\CustomerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CustomerController extends AbstractController
{
    /**
     * @Route("/Customers/List/Page/{page}", name="Customers_List", requirements={"page" = "\d+"})
     * @IsGranted("ROLE_ADMIN")
     *
     * @param int $page
     * @return array
     */
    public function Customers_List(CustomerRepository $customerRepository, $page): Response
    {
        $customersPerPage = 30;

        $customers = $customerRepository->getCustomers($page, $customersPerPage);

        $pagination = [
            'totalPages' => ceil(count($customers) / $customersPerPage),
            'page' => $page
        ];

        return $this->render('customers/index.html.twig', [
            'pagination' => $pagination,
            'title' => 'Liste des clients',
            'customers' => $customers
        ]);
    }

    /**
     * @Route("/Customers/{id}", name="Customers_Show")
     * @IsGranted("ROLE_ADMIN")
     */
    public function Customers_Show(Customer $customer)
    {
        return $this->render('customers/show.html.twig', [
            'title' => 'Consulter un client',
            'customer' => $customer
        ]);
    }

    /**
     * @Route("/Customers/{id}/Edit", name="Customers_Edit")
     * @IsGranted("ROLE_ADMIN")
     */
    public function Customers_Edit(Customer $customer, EntityManagerInterface $em, Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $form = $this->createForm(CustomerEditType::class, $customer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
        $data = $form->getData();

        if($data->getPlainPassword() != null)
        {
            $password = $passwordEncoder->encodePassword($customer, $customer->getPlainPassword());
            $customer->setPassword($password);
        }

        $em->flush();

        $this->addFlash('success', "Le client a été édité avec succès");

        return $this->redirectToRoute('Customers_List', ['page' => 1]);
        }
        return $this->render('customers/edit.html.twig', [
            'form' => $form->createView(),
            'title' => 'Editer un client',
            'customer' => $customer,
        ]);
    }
	
	/**
     * @Route("/Customers/{id}/Delete", name="Customers_Delete")
     * @IsGranted("ROLE_ADMIN")
     */
	public function Customers_Delete(Customer $customer, EntityManagerInterface $em, Request $request)
    {
        if ($customer == null)
            return $this->redirectToRoute('Customers_List', ['page' => 1]);

        $em->remove($customer);
        $em->flush();
		
		$this->addFlash('success', "Le client a été supprimé avec succès");
		
		return $this->redirectToRoute('Customers_List', ['page' => 1]);
	}
}
