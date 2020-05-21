<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @Route("/category/{name}_{id<\d+>}", name="subCategory")
     * @Route("/categories", name="categories")
     * @param CategoryRepository $repository
     * @param Category|null $category
     * @return Response
     */
    public function index(CategoryRepository $repository, Category $category=null)
    {
        if(!$category){
            //
            $category= $repository->find(1);
        }
        // dump(['category'=>$category]);
        return $this->render('category/index.html.twig', [
            'controller_name' => 'Category',
            'category'=>$category,
        ]);
    }
}
