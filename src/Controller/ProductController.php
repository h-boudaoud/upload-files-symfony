<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Image;
use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{


    /**
     * @Route("/product/new", name="product_new", methods={"get", "post", "put"})
     * @Route("/product/{name}_{id<\d+>}/edit", name="product_edit")
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param Product|null $product
     * @return Response
     */
    public function edit(Request $request, EntityManagerInterface $manager, Product $product = null)
    {
        $image = null;
        if (!$product) {
            $product = new Product();
        }

        $form = $this->createForm(ProductType::class, $product);

        try {
            $form->handleRequest($request);
        } catch (\Exception $e) {
            dd(['error' => $e]);
        }
//        if ($form->isSubmitted()) {
//            dd([
//                'form' => $form,
//                'files' => $form->get('image')->getData(),
//                'product' => $product
//            ]);
//        }
        if ($form->isSubmitted() && $form->isValid()) {
//            dump([
//                'form' => $form,
//                'files' => $form->get('images')->getData(),
//                'product' => $product
//            ]);

//            $files = $form->get('images')->getData();
//            dump(['edit files'=>$files]);

            $image = $form->get('images')->getData();

//            dd(['edit $image'=>$image->getImage()]);
            if ($image->getImage()) {
                try {
                    $product->addImage($image);
                    $manager->persist($product);
                    foreach ($product->getImages() as $image) {
                        $manager->persist($image);
                    }
                    $manager->flush();
//            dd([
//                'form' => $form->get('images')->getData(),
//                'form Error' => $form->getErrors(),
//                'product' => $product,
//                'image' => $image
//            ]);

                    return $this->redirectToRoute('product_by_id', ['id' => $product->getId(), 'name' => str_replace(' ', '-', $product->getName())], 301);

                } catch (\Exception $e) {
                    $form->addError(new FormError("Error Upload file"));

                    $form->get('images')->get('image')->addError(new FormError($e->getMessage()));

                }
            }


        }

//        dump([
//            'form' => $form,
//            'form Error' => $form->getErrors(),
//            'product' => $product,
//            'files' => $form->get('images')->getData(),
//        ]);

        // dump(['product' => $product, 'image' => $image]);
        return $this->render('product/edit.html.twig', [
            'controller_name' => 'Product',
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/product/{name}_{id<\d+>}/details", name="product_details")
     * @Route("/product/{name}_{id<\d+>}", name="product_by_id")
     * @param Product|null $product
     * @return Response
     */
    public function show(Product $product = null)
    {

        if (!$product) {
            return $this->redirectToRoute('products', [], 301);
        }

        // dump(['product' => $product, 'image' => $image]);
        return $this->render('product/show.html.twig', [
            'controller_name' => 'Product',
            'product' => $product,
        ]);
    }

    /**
     * @Route("/product/{id<\d+>}/delete", name="product_delete" , methods={"delete"})
     * @param ObjectManager $manager
     * @param Product|null $product
     * @return JsonResponse
     * @throws \Exception
     */
    public function DeleteProduct(ObjectManager $manager, Product $product = null)
    {
        $id = null;
        $current_dir_path = null;
        if ($product) {
            $id = $product->getId();
            foreach ($product->getImages() as $image) {
                $product->removeImage($image);
                $manager->remove($image);
            }

            $manager->remove($product);
            $manager->flush();


            return $this->json(
                [
                    'data' => [
                        'id' => $id,
                        'code' => '200',
                        'message' => 'success',
                    ]
                ], 200);
        }
        return $this->json(
            [
                'data' => [
                    'id' => $id,
                    'code' => '400',
                    'message' => 'The product does not exist',
                ]
            ], 400);

    }


    /**
     * @Route("/category/{name}_{id<\d+>}/products", name="products_by_category")
     * @Route("/products", name="products")
     * @param ProductRepository $repository
     * @param Category|null $category
     * @return Response
     */
    public function index(
        ProductRepository $repository,
        Category $category = null)
    {
        $products = null;
        if ($category) {
            $products = $category->getProductsAllCategories();
        } else {
            $products = $repository->findAll();
        }
//          dump(['product'=>$products, 'category', $category]);
        return $this->render('product/index.html.twig', [
            'controller_name' => 'Product',
            'products' => $products,
            'category' => $category,
        ]);
    }


}
