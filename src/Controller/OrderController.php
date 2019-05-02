<?php

namespace App\Controller;

use App\Entity\Card;
use App\Entity\Orders;
use App\Form\OrderProductsAddType;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    /**
     * @Route("/Orders/Status/{status}", name="Admin_GetAllOrders")
     * @IsGranted("ROLE_ADMIN")
     */
    public function Admin_GetAllOrders($status, OrderRepository $orderRepository): Response
    {
        if (is_numeric($status)) {
            return $this->redirectToRoute('Admin_GetAllOrders', ['status' => 'En attente de paiement']);
        }

        $sql = $orderRepository->getByStatus($status);

        $orders_progress = $orderRepository->getByStatus('En cours de commande');

        $orders_wait = $orderRepository->getByStatus('En attente de paiement');

        $orders_prepare = $orderRepository->getByStatus('Commande en préparation');

        $orders_finish = $orderRepository->getByStatus('Commande terminée');

        $orders_cancel = $orderRepository->getByStatus('Commande annulée');

        return $this->render('orders/index.html.twig', [
            'orders' => $orderRepository->findAll(),
            'orders_cancel' => $orders_cancel,
            'orders_finish' => $orders_finish,
            'orders_prepare' => $orders_prepare,
            'orders_progress' => $orders_progress,
            'orders_wait' => $orders_wait,
            'title' => 'Liste des commandes',
            't' => $sql,
            'status' => $status
        ]);
    }

    /**
     * @Route("/Orders/{id}", name="Admin_GetOrderPerId")
     * @IsGranted("ROLE_ADMIN")
     */
    public function Admin_GetOrderPerId(Request $request, OrderRepository $orderRepository, EntityManagerInterface $em, $id): Response
    {
        $orderId = $orderRepository->find($id);
        $orderAdd = new Card();

        $form = $this->createForm(OrderProductsAddType::class, $orderAdd);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $orderAdd->setOrders($orderId);
            $em->persist($orderAdd);
            $em->flush();
        }

        if($orderId != null)
        {
            $items = $orderId->getCards();
            return $this->render('orders/show.html.twig', [
                'form' => $form->createView(),
                'items' => $items,
                'result' => $orderId,
                'title' => 'Consulter une commande',
                'total' => $orderId->getOrderTotal()
            ]);
        } else {
            $this->addFlash('warning', "La commande demandée n'existe pas");

            return $this->redirectToRoute('Admin_GetAllOrders', ['status' => 'En attente de paiement']);
        }
    }

    /**
     * @Route("/Orders/{id}/SetPay", name="Admin_SetOrderPay")
     * @IsGranted("ROLE_ADMIN")
     */
    public function Admin_SetOrderPay($id, Orders $order, EntityManagerInterface $em): Response
    {
        if($order->getStatus() == "En attente de paiement") {
            $order->setStatus("Commande en préparation");
            $em->flush();
            return $this->redirectToRoute('Admin_GetAllOrders', ['status' => 'En attente de paiement']);
        } else {
            return $this->redirectToRoute('Admin_GetAllOrders', ['status' => 'En attente de paiement']);
        }
    }

    /**
     * @Route("/Orders/{id}/SetCancel", name="Admin_SetOrderCancel")
     * @IsGranted("ROLE_ADMIN")
     */
    public function Admin_SetOrderCancel($id, Orders $order, EntityManagerInterface $em): Response
    {
        if($order->getStatus() == "En attente de paiement") {
            $order->setStatus("Commande annulée");
            $em->flush();
            return $this->redirectToRoute('Admin_GetAllOrders', ['status' => 'En attente de paiement']);
        } else {
            return $this->redirectToRoute('Admin_GetAllOrders', ['status' => 'En attente de paiement']);
        }
    }

    /**
     * @Route("/Orders/{id}/SetFinish", name="Admin_SetOrderFinish")
     * @IsGranted("ROLE_ADMIN")
     */
    public function Admin_SetOrderFinish($id, Orders $order, EntityManagerInterface $em): Response
    {
        if($order->getStatus() == "Commande en préparation") {
            $order->setStatus("Commande terminée");
            $em->flush();
            return $this->redirectToRoute('Admin_GetAllOrders', ['status' => 'En attente de paiement']);
        } else {
            return $this->redirectToRoute('Admin_GetAllOrders', ['status' => 'En attente de paiement']);
        }
    }

    /**
     * @Route("/Orders/{order_id}/Delete/{product_id}", name="Admin_DeleteProductFromOrder")
     */
    public function Admin_DeleteProductFromOrder($order_id, $product_id, EntityManagerInterface $em): Response
    {
        $ordersRepository = $em->getRepository(Card::class);
        $result = $ordersRepository->find($product_id);

        if($result != null)
        {
            foreach($result->getCardAllLines() as $ingredient)
            {
                $em->remove($ingredient);
            }

            $em->remove($result);
            $em->flush();
            $this->addFlash('success', "Le produit a été supprimé de la commande");
        }

        return $this->redirectToRoute('Admin_GetOrderPerId', ['id' => $order_id]);
    }

}
