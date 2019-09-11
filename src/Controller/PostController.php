<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

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
    	return $this->render('post/index.html.twig',['post_id'=>$slug, 'logged_user'=>$this->session->get('logged_user')]);
        /*return $this->render('post/index.html.twig', [
            'controller_name' => 'PostController',
        ]);*/
    }

}
