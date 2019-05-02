<?php

namespace App\Controller;

use App\Repository\OrderRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/", name="Admin_Home")
     * @IsGranted("ROLE_ADMIN")
     */
    public function Index()
    {
        return $this->render('index.html.twig', [
            'title' => 'Panel - Accueil'
        ]);
    }
    /**
     * @Route("/GetReceipt/{id}", name="Admin_GetReceipt")
     * @IsGranted("ROLE_ADMIN")
     */
    public function Admin_GetReceipt(OrderRepository $orderRepository, $id)
    {
        $order = $orderRepository->find($id);

        $date = date("d/m/Y H:i:s");

        return $this->render('receipt/index.html.twig', [
            'date' => $date,
            'id' => $id,
            'order' => $order,
        ]);
    }
}
