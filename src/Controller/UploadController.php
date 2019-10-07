<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Service\FileUploader;

class UploadController extends AbstractController
{
    /**
     * @Route("/doUpload", name="upload")
     */
    public function index(Request $request, string $uploadDir, FileUploader $uploader)
    {
    	$file = $request->files->get('myfile');

        if (empty($file)) 
        {
            return new Response("No file specified",  
               Response::HTTP_UNPROCESSABLE_ENTITY, ['content-type' => 'text/plain']);
        }        

        $filename = $file->getClientOriginalName();
        $uploader->upload($uploadDir, $file, $filename);

        return new Response("File uploaded",  Response::HTTP_OK, ['content-type' => 'text/plain']);         
        
        /*return $this->render('upload/index.html.twig', [
            'controller_name' => 'UploadController',
        ]);*/
    }
}
