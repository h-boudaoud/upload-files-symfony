<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Image;
use App\Entity\Product;
use App\Form\ImageType;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route("/product/new", name="product_new", methods={"get", "post", "put"})
     * @Route("/product/{name}-{id<\d+>}", name="product_edit")
     * @param Request $request
     * @param Product|null $product
     * @return Response
     */
    public function edit(Request $request, ObjectManager $manager, Product $product = null)
    {
        if (!$product) {
            $product = new Product();
        }
        $form = $this->createForm(ProductType::class, $product);
        if (!$product) {
            $product = new Product();
        }
        $form->handleRequest($request);
//        if ($form->isSubmitted()) {
//            dump(['form' => $form->get('images'), 'product' => $product]);
//        }
        if ($form->isSubmitted() && $form->isValid()) {
            // $image = new Image();
            $image = $form->get('images')->getData();
            if ($image->saveTo == 'folder') {
                $floder = "asset/images/" . $image->getCreatedAt()->format('Y-m-d/');
                $image->setFolder($floder);
                $image->getImage()->move($floder, $image->getImageName());
            }
            $product->addImage($image);
            //$image->setProduct($product);
            $manager->persist($product);
            $manager->flush();
            dump(['form' => $form->get('images')->getData()->getImage(), 'product' => $product, 'image' => $image]);

        }
        return $this->render('product/edit.html.twig', [
            'controller_name' => 'ProductController',
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/category/{name}-{id<\d+>}", name="products_by_category")
     * @Route("/products", name="products")
     * @param ProductRepository $repository
     * @param Category|null $category
     * @return Response
     */
    public function index(ProductRepository $repository, Category $category = null)
    {
        $products = null;
        if ($category) {
            $products = $category->getProducts();
            foreach ($category->getSubCategories() as $subCategory) {
                $products = array_merge($products, $subCategory->getProducts());
            }
        } else {
            $products = $repository->findAll();
        }
        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
            'products' => $products,
            'category' => $category,
        ]);
    }

}
