<?php

namespace App\Controller;

use App\Entity\Card;
use App\Repository\AllIngredientInProductRepository;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class AjaxController extends AbstractController
{
    /**
     * @Route("/Orders/{order_id}/AddProduct", name="Admin_OrderAddProduct_ajax")
     */
    public function Admin_OrderAddProduct($order_id, Request $request, EntityManagerInterface $em, ProductRepository $productRepository, OrderRepository $orderRepository): Response
    {
        $retour = array('status' => false, 'composed' => false);
        $idproduct = $request->request->get('idproduct');
        $orderId = $orderRepository->find($order_id);
        $qty = $request->request->get('qty');
        $product = $productRepository->find($idproduct);


        if($product != null && $orderId != null)
        {
            if($product->getType() == 2 || $product->getType() == 3)
            {
                $retour['composed'] = true;
                $retour['status'] = true;
                $retour['product'] = $idproduct;
                $retour['producttype'] = $product->getType();
                $retour['order'] = $order_id;
                $retour['qty'] = $qty;

                # liste des ingredients
                $retour['ingredients'] = array();

                $painlist = array();
                $retour['ingredients'][1] = $painlist;

                $saucelist = array();
                $retour['ingredients'][3] = $saucelist;

                $accompagnementlist = array();
                $retour['ingredients'][4] = $accompagnementlist;

                $cuissonlist = array();
                $retour['ingredients'][5] = $cuissonlist;
            }
            else
            {
                $orderAdd = new Card();
                $orderAdd->setOrders($orderId);
                $orderAdd->setProduct($product);
                $orderAdd->setQuantity($qty);
                $em->persist($orderAdd);
                $em->flush();
                $retour['status'] = true;
            }
        }

        return $this->json($retour);
    }
    /**
     * @Route("/Orders/{order_id}/AddProductComposed", name="Admin_OrderAddProductComposed_ajax")
     */
    public function Admin_OrderAddProductComposed($order_id, Request $request, EntityManagerInterface $em, OrderRepository $orderRepository, ProductRepository $productRepository) :Response
    {
        $retour = array('status' => false);

        $orderid = $request->request->get('order');
        $productid = $request->request->get('product');
        $painid = $request->request->get('pain');
        $sauceid = $request->request->get('sauce');
        $accompagnementid = $request->request->get('accompagnement');
        $cuissonid = $request->request->get('cuisson');

        $qty = $request->request->get('qty');

        $order = $orderRepository->find($orderid);
        $product = $productRepository->find($productid);

        if($order != null && $product != null)
        {
            $retour['status'] = true;

            # Ajout d'un produit au panier
            $card = new Card();
            $card->setProduct($product);
            $card->setOrders($order);
            $card->setInProgress(false);
            $card->setQuantity($qty);
            $em->persist($card);
            $em->flush();
        }

        return $this->json($retour);
    }
    /**
     * @Route("/Ajax/Delete/CurrentOrder/{borne}", name="Delete_CurrentOrder")
     */
    public function Delete_CurrentOrder($borne, OrderRepository $orderRepository, EntityManagerInterface $em)
    {
        $retour = array();
        $order = $orderRepository->findOneBy(['status' => 'En cours de commande', 'borneID' => $borne]);

        if ($order != null)
        {
            $em->persist($order->setStatus('Commande annulée'));
            $em->flush();
            $retour[] = array('status' => true);
        }
        else
        {
            $retour[] = array('status' => false);
        }

        return $this->json($retour);
    }
    /**
     * @Route("/AllComposed/ajaxtype/{product_id}/{type}/{cardid}", name="allingredient_gettype_status")
     */
    public function getbyType($product_id, $type, $cardid, AllIngredientInProductRepository $allIngredientInProductRepository): Response
    {
        $retour = array();

        $items = $allIngredientInProductRepository->findBy(['product' => $product_id]);

        foreach($items as $ingredient)
        {
            if ($ingredient->getIngredient()->getType() == $type)
                $retour[] = array('id' => $ingredient->getIngredient()->getId(), 'name' => $ingredient->getIngredient()->getName(), 'picture' => $ingredient->getIngredient()->getPicture(), 'type' => $ingredient->getIngredient()->getType(), 'price' => $ingredient->getIngredient()->getPrice(), 'available' => $ingredient->getIngredient()->getAvailable());
        }

        return $this->json($retour);
    }

    /**
     * @Route("/Ajax/RemoveSelectedIngredientInProduct", name="Ajax_RemoveSelectedIngredientInProduct")
     */
    public function RemoveSelectedIngredientInProduct(Request $request, AllIngredientInProductRepository $allIngredientInProductRepository, EntityManagerInterface $em)
    {
        $retour = array('status' => false);
        $id = $request->request->get('id');

        $retour['id'] = $id;

        $line = $allIngredientInProductRepository->find($id[0]);

        foreach ($line->getProduct()->getAllIngredientInProducts() as $l)
        {
            $em->remove($l);
            $em->flush();
        }

        return $this->json($retour);
    }
    /**
     * @Route("/Ajax/Refresh/OrdersList", name="Refresh_OrdersList")
     */
    public function Refresh_OrdersList(OrderRepository $orderRepository)
    {
        $orders = $orderRepository->findAll();

        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        $normalizer = new ObjectNormalizer($classMetadataFactory);
        $serializer = new Serializer(array($normalizer));

        $data = $serializer->normalize($orders, 'json', array('groups' => array('detail')));

        return $this->json($data);
    }
}
