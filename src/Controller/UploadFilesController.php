<?php

namespace App\Controller;

use App\Entity\UploadArrayFiles;
use App\Form\UploadFilesType;
use Safe\Exceptions\AbstractSafeException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UploadFilesController extends AbstractController
{
    /**
     * @Route("/upload/", name="upload")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $listUrlFiles = null;
        $newFiles = new UploadArrayFiles();
        $form = $this->createForm(UploadFilesType::class, $newFiles);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            foreach ($newFiles->getFiles() as $file) {
                $mimetype = $file->getMimeType() == "image/svg"?"image/svg+xml" : $file->getMimeType();
                $urlFile = [
                    'name'=>$file->getClientOriginalName(),
                    'saveTo' => 'folder',
                    'mimeType' => $mimetype
                ];
                try {
                    if ($newFiles->getSaveTo() === 'folder') {
                        $folder = $file->getClientOriginalName();
                        $file->move('asset/Uploads/', $folder);
                        $urlFile["data"] = 'asset/Uploads/' . $folder;
                    } else if ($newFiles->getSaveTo() == 'database') {
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
            dump(['$listUrlFiles' => $listUrlFiles]);

        }
        return $this->render('upload_files/index.html.twig', [
            'controller_name' => 'UploadFilesController',
            'form' => $form->createView(),
            'listUrlFiles' => $listUrlFiles,
        ]);
    }
}
