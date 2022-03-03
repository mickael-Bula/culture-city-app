<?php

namespace App\Controller\Front;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @Route("/category/{slug}", name="show_category", methods={"GET"})
     */
    public function showCategoryByName(CategoryRepository $categoryRepository, EventRepository $eventRepository, string $slug): Response
    {
        // catch category
        $category = $categoryRepository->findOneBy(['slug' => $slug]);

        $categorySlug= $category->getSlug();

        // catch Events form this category orderby date
        $eventsList = $eventRepository->findByCategory($categorySlug);

        return $this->render('front/main/category.html.twig', compact('category','eventsList'));
    }
   
   
}
