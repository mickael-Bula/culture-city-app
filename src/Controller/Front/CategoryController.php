<?php

namespace App\Controller\Front;

use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @Route("/category/{slug}", name="show_category", methods={"GET"})
     */
    public function showCategoryByName(Category $category): Response
    {
      
        return $this->render('front/main/category.html.twig', compact('category'));
    }
   
}
