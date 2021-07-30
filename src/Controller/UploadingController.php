<?php

namespace App\Controller;

use App\Business\FileBusiness;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\File;

class UploadingController extends AbstractController
{
    /**
     * @Route("/upload_image", name="app_upload_image")
     */
    public function uploadImage(Request $request, FileBusiness $fileBusiness)
    {
        $image = $request->files->get('image');
        
        $file = $fileBusiness->uploadFile($image);

        return $this->json([
            'success' => 1,
            'file' => [
                'url' => $this->generateUrl('app_download_file', ['id' => $file->getId()]) 
            ],
        ]);
    }

    /**
     * @Route("/upload_url",name="app_upload_url")
     */
    public function uploadUrl(Request $request)
    {
        dump($request->request->all());

        die();

        return new Response();
    }

    /**
     * @Route("/download_file/{id}", name="app_download_file", requirements={"id": "\d+"})
     */
    public function downloadFile(File $file, FileBusiness $fileBusiness)
    {
        return $fileBusiness->downloadFile($file);
    }
}