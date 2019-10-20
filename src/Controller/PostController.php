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
        //return $this->redirectToRoute('invalidArticle');
        //dump(is_numeric($slug));
        //exit();

        if(is_numeric($slug))
        {
            //request article data
            $data = $this->getDoctrine()->getRepository(Article::class)->ArticleData($slug);
            //dump($data);
        }
        else
        {
            //invalid indentifier
            //give empty page
            return $this->redirectToRoute('invalidArticle');
        }

        if( file_exists($this->session->get('user_avatar') ) )
        {
            return $this->render('post/index.html.twig',['post_id'=>$slug, 'logged_user'=>$this->session->get('logged_user'), 'user_avatar'=>$this->session->get('user_avatar'), 'data'=>$data]);
        }
        else
        {
            return $this->render('post/index.html.twig',['post_id'=>$slug, 'logged_user'=>$this->session->get('logged_user'), 'user_avatar'=>false, 'data'=>$data]);
        }
    }

    /**
     * @Route("/post_error", name="invalidArticle")
     */
    public function InvalidArticle()
    {
        if( file_exists($this->session->get('user_avatar') ) )
        {
            return $this->render('errors/invalid_article.html.twig',['logged_user'=>$this->session->get('logged_user'), 'user_avatar'=>$this->session->get('user_avatar')]);
        }
        else
        {
            return $this->render('errors/invalid_article.html.twig',['logged_user'=>$this->session->get('logged_user'), 'user_avatar'=>false]);
        }
        
    }

}
