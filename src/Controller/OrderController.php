<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\Product;
use App\Form\OrderType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    /**
     * @Route("/admin/orders", name="admin.orders_list")
     */
    public function index()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $orders = $entityManager->getRepository(Order::class)->findAll();

        return $this->render('admin/orders.html.twig', [
            'orders' => $orders,
        ]);
    }

    /**
     * @Route("/orders/{id}/add", name="add_order")
     */
    public function addOrder(Product $product, Request $request)
    {
        $order = new Order();
        $order->setProduct($product);
        $orderForm = $this->createForm(OrderType::class, $order);

        $orderForm->handleRequest($request);
        if($orderForm->isSubmitted() && $orderForm->isValid())
        {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($order);
            $entityManager->flush();
            $this->addFlash('success', "The Order was added successfully!");
            return $this->redirect('/');
        }

        return $this->render('home/order.html.twig', [
            'orderForm'=> $orderForm->createView(),
        ]);
    }

    /**
     * @Route("/admin/orders/{id}/delete", name="admin.order_delete")
     */
    public function delete(Order $order, Request $request)
    {
        if($this->isCsrfTokenValid('delete' . $order->getId(), $request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($order);
            $entityManager->flush();
            $this->addFlash('success', "The Order was deleted successfully!");
        }
        return $this->redirect('/admin/orders');
    }
}
