<?php


namespace App\Service;

use App\Repository\CategoryRepository;


class PrincipalCategories
{
    public function principalCategories(CategoryRepository $repository = null)
    {
        //return $repository->findBy(['subCategory'=>null]);
        return [
             [
                 'id' => 1,
                 'name' => 'toto',
                "subCategories" => [
                    ['id' => 11,"name" => "subToto1"],
                    ['id' => 12,"name" => "subToto2"],
                    ['id' => 13,"name" => "subToto3"],
                    ['id' => 14,"name" => "subToto4"],
                ]
            ],
            [
                'id' => 2,
                'name' => 'tata',
                "subCategories" => [
                    ['id' => 21,"name" => "subTata3"],
                    ['id' => 22,"name" => "subTata4"],
                ]
            ],
            [
            'id' => 3,
            'name' => 'tutu',
            "subCategories" =>null
            ]
        ];

    }

}
