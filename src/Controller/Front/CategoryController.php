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
     * @Route("/category/{slug}", name="show_category", methods={"GET", "POST"})
     */
    public function showCategoryByName(CategoryRepository $categoryRepository, EventRepository $eventRepository, string $slug): Response
    {
        // catch category
        $category = $categoryRepository->findOneBy(['slug' => $slug]);

        // get Events from the category ordered By date
        $eventsList = $eventRepository->findByCategory($slug);

        //Custom request on Category repository 
        $premiumEvents = $categoryRepository->findCurrentCategoryPremiumEventsDQL($category);

        return $this->render('front/main/category.html.twig', compact('category','eventsList','premiumEvents'));
    }

   
}
