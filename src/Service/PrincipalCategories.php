<?php


namespace App\Service;

use App\Repository\CategoryRepository;


class PrincipalCategories
{
    /**
     * @var CategoryRepository
     */
    private $repository;

    public function __construct(CategoryRepository $repository)
    {
        $this->repository = $repository;
    }

    public function principalCategories()
    {
//       dd(['all=>'=> $this->repository->findAll()]);
//        dim($this->repository->find(1));
        return $this->repository->findAll();
//        return [
//             [
//                 'id' => 1,
//                 'name' => 'toto',
//                "subCategories" => [
//                    ['id' => 11,"name" => "subToto1"],
//                    ['id' => 12,"name" => "subToto2"],
//                    ['id' => 13,"name" => "subToto3"],
//                    ['id' => 14,"name" => "subToto4"],
//                ]
//            ],
//            [
//                'id' => 2,
//                'name' => 'tata',
//                "subCategories" => [
//                    ['id' => 21,"name" => "subTata3"],
//                    ['id' => 22,"name" => "subTata4"],
//                ]
//            ],
//            [
//            'id' => 3,
//            'name' => 'tutu',
//            "subCategories" =>null
//            ]
//        ];

    }

}
