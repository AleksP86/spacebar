<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Service\FileUploader;

use App\Entity\UserData;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class UploadController extends AbstractController
{
    /**
     * @Route("/doUpload", name="upload")
     */
    public function index(Request $request, string $uploadDir, FileUploader $uploader, SessionInterface $session)
    {
    	$file = $request->files->get('myfile');
        $max_file_size =(int)ini_get("upload_max_filesize")*1024*1024;

        /*
        dump($file);
        dump($max_file_size);
        dump($file->getSize());
        */

        if (empty($file))
        {
            return new Response("No file specified",Response::HTTP_UNPROCESSABLE_ENTITY, ['content-type' => 'text/plain']);
        }
        if($file->getSize()<1 || $file->getSize()>$max_file_size)
        {
            return new Response("File too large",Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $filename = $file->getClientOriginalName();
        $uploader->upload($uploadDir, $file, $filename);
        $this->getDoctrine()->getRepository(UserData::class)->UpdateAvatar($filename, $session->get('logged_user_id'));
        $session->set('user_avatar', "/uploads/".$filename);

        return $this->redirectToRoute('profile');

        //return new Response("File uploaded",  Response::HTTP_OK, ['content-type' => 'text/plain']);         
        
        /*return $this->render('upload/index.html.twig', [
            'controller_name' => 'UploadController',
        ]);*/
    }
}
