<?php

namespace App\Controller;

use App\Entity\Image;
use App\Form\ImageType;
use App\Form\UploadFilesType;
use App\Service\UploadArrayFiles;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UploadFilesController extends AbstractController
{


    /**
     * @Route("/upload/image", name="upload_image")
     * @param Request $request
     * @param ObjectManager $manager
     * @return Response
     */
    public function uploadImage(Request $request, ObjectManager $manager)
    {
        $newImage = new Image();
        $image = null;
        $form = $this->createForm(ImageType::class, $newImage );
        $form->handleRequest($request);
//        if ($form->isSubmitted()) {
//            dump(['request' => $request,
//                'submitted' => $form->isSubmitted(),
//                'valid' => $form->isValid(),
//                'data' => $form->getData()]);
//        }
        if ($form->isSubmitted() && $form->isValid()) {
//            dump(['request' => $request,
//                'submitted' => $form->isSubmitted(),
//                'valid' => $form->isValid(),
//                'data' => $form->getData()]);
            if ($newImage->saveTo=='folder') {
                $floder ="asset/images/" . $newImage->getCreatedAt()->format('Y-m-d/');
                $newImage->setFolder($floder);
                $newImage->getImage()->move($floder,$newImage->getImageName());
            }
            $manager->persist($newImage);
            $manager->flush();
        }
        return $this->render('upload_files/image.html.twig', [
            'controller_name' => 'UploadFilesController',
            'form' => $form->createView(),
            'image' => ($form->isSubmitted() && $form->isValid()) ? $newImage : null,
        ]);
    }





    /**
     * @Route("/upload/", name="upload")
     * @param Request $request
     * @param UploadArrayFiles $uploadArrayFiles
     * @return Response
     */
    public function index(Request $request, UploadArrayFiles $uploadArrayFiles)
    {
        $listUrlFiles = null;
        $form = $this->createForm(UploadFilesType::class, $uploadArrayFiles);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
//             dump(['request'=>$request,
//                'submitted'=>$form->isSubmitted(),
//                'valid'=>$form->isValid(),
//                'data'=>$form->getData()]);

            foreach ($uploadArrayFiles->getFiles() as $file) {
                $mimetype = $file->getMimeType() == "image/svg"?"image/svg+xml" : $file->getMimeType();
                $urlFile = [
                    'name'=>$file->getClientOriginalName(),
                    'saveTo' => 'folder',
                    'mimeType' => $mimetype
                ];
                try {
                    if ($uploadArrayFiles->getSaveTo() === 'folder') {
                        $folder = $file->getClientOriginalName();
                        $file->move('asset/Uploads/', $folder);
                        $urlFile["data"] = 'asset/Uploads/' . $folder;
                    } else if ($uploadArrayFiles->getSaveTo() == 'database') {
                        $urlFile["data"] =base64_encode(\file_get_contents($file));
                        $urlFile['saveTo'] = 'database';
                    }
                    $listUrlFiles[$file->getClientOriginalName()] = $urlFile;


                } catch (\Exception $e) {
                    $urlFile["data"]  = $e->getMessage();
                    $urlFile['error'] = 'error' ;

                    $listUrlFiles[$file->getClientOriginalName()] = $urlFile;
                }
            }
            // dump(['$listUrlFiles' => $listUrlFiles]);

        }
        return $this->render('upload_files/base.html.twig', [
            'controller_name' => 'UploadFilesController',
            'form' => $form->createView(),
            'listUrlFiles' => $listUrlFiles,
        ]);
    }
}
