<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

use App\Entity\Article;

class PostController extends AbstractController
{
	public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }
    /**
     * @Route("/post/{slug}", name="post")
     */
    public function index($slug)
    {
    	//dump($slug);

        //obtain article data
        $data = $this->getDoctrine()->getRepository(UserData::class)->ArticleData($slug);

        if( file_exists($this->session->get('user_avatar') ) )
        {
            return $this->render('post/index.html.twig',['post_id'=>$slug, 'logged_user'=>$this->session->get('logged_user'), 'user_avatar'=>$this->session->get('user_avatar')]);
        }
        else
        {
            return $this->render('post/index.html.twig',['post_id'=>$slug, 'logged_user'=>$this->session->get('logged_user'), 'user_avatar'=>false]);
        }
    }

}
