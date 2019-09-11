<?php

namespace App\Controller;

use App\Entity\UserData;
use App\Entity\Article;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ProfileController extends AbstractController
{
	public function __construct(SessionInterface $session)
    {
       $this->session = $session;
    }

    /**
     * @Route("/profile", name="profile")
     */
    public function index()
    {
    	$user_d= $this->getDoctrine()->getRepository(UserData::class)->ProfileData($this->session->get('logged_user_id'));
    	$story_count=$this->getDoctrine()->getRepository(Article::class)->getUserStories($this->session->get('logged_user_id'));

    	$comment_count=0;
    	
    	/*dump($user_d);
    	dump($story_count);*/
        return $this->render('profile/index.html.twig', [
            'controller_name' => 'ProfileController', 'logged_user'=>$this->session->get('logged_user')
            , 'user_data'=>$user_d[0], 'story_count'=>$story_count['stories'],'comment_count'=>$comment_count
        ]);
    }
}
