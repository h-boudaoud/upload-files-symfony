<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $categories = [];
        $arrayCategories =[
            "Reflexion"=>["strategies","logique", "pions", "enquêtes"],
            "loisirs"=>["ambiances", "memoire", "connaissances", "lettres", "créatifs"],
            "divers"=>["educatif", "cooperatof", "addresse", "rôle", "gestion", "autres"],
            "accesoires"=>[]
        ];
        $allCategory = new Category();
        $allCategory->setName("All Categories");

        $manager->persist($allCategory);
        foreach ($arrayCategories as $category=>$subCategories){
            $newCategory = new Category();
            $newCategory->setSubCategory($allCategory);
            $newCategory->setName($category);
            foreach ($subCategories as $subCategory) {
                $newSubCategory = new Category();
                $newSubCategory->setName($subCategory);
                $newSubCategory->setSubCategory($newCategory);
                $manager->persist($newSubCategory);
            }

            $manager->persist($newCategory);
            $categories[]=$newCategory;
        }

        // $product = new Product();

        $manager->flush();
    }
}
