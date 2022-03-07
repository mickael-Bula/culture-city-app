<?php

namespace App\Controller\Front;

use App\Entity\Event;
use App\Entity\Category;
use App\Repository\EventRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryController extends AbstractController
{
    /**
     * @Route("/category/{slug}", name="show_category", methods={"GET"})
     */
    public function showCategoryByName(CategoryRepository $categoryRepository, EventRepository $eventRepository, string $slug): Response
    {
        // catch category
        $category = $categoryRepository->findOneBy(['slug' => $slug]);

        $categorySlug = $category->getSlug();

        // catch Events form this category orderby date
        $eventsList = $eventRepository->findByCategory($categorySlug);
 
        // catch category for dynamize custom request parameter with current category 
        $catparam = $categoryRepository->findOneBy(['slug' => $slug]);
        //Custom request on Category repository 
        $premiumEvents = $categoryRepository->findCurrentCategoryPremiumEventsDQL($catparam);

        return $this->render('front/main/category.html.twig', compact('category','eventsList','premiumEvents'));
    }

   
}
