<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route("/admin/products", name="admin.products_list")
     */
    public function index(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $products = $entityManager->getRepository(Product::class)->findAll();

        $product = new Product();
        $productForm = $this->createForm(ProductType::class, $product);

        $productForm->handleRequest($request);
        if($productForm->isSubmitted() && $productForm->isValid())
        {
            $entityManager->persist($product);
            $entityManager->flush();
            $this->addFlash('success', "The Product was added successfully!");
            return $this->redirect('/admin/products');
        }


        return $this->render('admin/products.html.twig', [
            'products' => $products,
            'productForm'=> $productForm->createView(),
        ]);
    }

    /**
     * @Route("/admin/products/{id}/delete", name="admin.product_delete")
     */
    public function delete(Product $product, Request $request)
    {
        if($this->isCsrfTokenValid('delete' . $product->getId(), $request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($product);
            $entityManager->flush();
            $this->addFlash('success', "The Product was deleted successfully!");
        }
        return $this->redirect('/admin/products');
    }
}
