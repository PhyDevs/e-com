<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @Route("/admin/categories", name="admin.categories_list")
     */
    public function index(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $categories = $entityManager->getRepository(Category::class)->findAll();

        $category = new Category();
        $categoryForm = $this->createForm(CategoryType::class, $category);

        $categoryForm->handleRequest($request);
        if($categoryForm->isSubmitted() && $categoryForm->isValid())
        {
            $entityManager->persist($category);
            $entityManager->flush();
            $this->addFlash('success', "The Category was added successfully!");
            return $this->redirect('/admin/categories');
        }

        return $this->render('admin/categories.html.twig', [
            'categories' => $categories,
            'categoryForm' => $categoryForm->createView()
        ]);
    }

    /**
     * @Route("/admin/categories/{id}/delete", name="admin.category_delete")
     */
    public function delete(Category $category, Request $request)
    {
        if($this->isCsrfTokenValid('delete' . $category->getId(), $request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($category);
            $entityManager->flush();
            $this->addFlash('success', "The Category was deleted successfully!");
        }
        return $this->redirect('/admin/categories');
    }

}
