<?php

namespace App\Controller\Front;

use App\Repository\CategoryRepository;
use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @Route("/category/{slug}", name="category", methods={"GET"})
     */
    public function showCategorySlug(CategoryRepository $categoryRepository, EventRepository $eventRepository, string $slug): Response
    {
        $category = $categoryRepository->findOneBy(["slug"=>$slug]);


        // keep catgory id
        $categoryId = $category->getId();
        
        // display all event form this category
        $eventsList = $eventRepository->findBy(["category"=>$categoryId ]);

        // error catch events
        if (!$eventsList)
        {
            throw $this->createNotFoundException('No event to display');
        }

        return $this->render('front/category/index.html.twig', compact('category', 'eventsList'));
        
    }
   
}
