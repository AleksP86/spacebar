<?php

namespace App\Controller;

use App\Entity\Article;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

use Doctrine\ORM\EntityManagerInterface;

class ArticleController extends AbstractController
{
	public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }
    /**
     * @Route("/article", name="article")
     */
    /*
    public function index()
    {
        return $this->render('article/index.html.twig', [
            'controller_name' => 'ArticleController',
        ]);
    }
    */


    /**
     * @Route("/posts", name="posts")
     */
    public function indexAdd()
    {
        if( file_exists($this->session->get('user_avatar') ) )
        {
            return $this->render('posting/index.html.twig', [
            'controller_name' => 'ArticleController', 'logged_user'=>$this->session->get('logged_user'), 'user_avatar'=>$this->session->get('user_avatar')
        ]);
        }
        else
        {
            return $this->render('posting/index.html.twig', [
            'controller_name' => 'ArticleController', 'logged_user'=>$this->session->get('logged_user'), 'user_avatar'=>false
        ]);
        }
    }

    /**
    * @Route("/addPost", name="addPost", methods="POST")
    */
    public function AddPost(EntityManagerInterface $em, SessionInterface $session)
    {
        //$errorMessage="";
        try {
		    $post = new Article();
	    	$post->setName($_POST['title'])->setText($_POST['text'])->setAddedAt(date_create())->setAuthor((int)$session->get('logged_user_id'));
	    	$em->persist($post);
	        $em->flush();
		} 
		catch(DBALException $e){
		    $errorMessage = $e->getMessage();
		    return new JsonResponse(['status'=>false, 'error'=>$errorMessage]);
		}    
		catch(\Exception $e){
		    $errorMessage = $e->getMessage();
		    return new JsonResponse(['status'=>false, 'error'=>$errorMessage]);
		}
		return new JsonResponse(['status'=>true,'id'=>$post->getId()]);
    }
}
