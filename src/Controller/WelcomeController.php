<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Article;

//use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class WelcomeController extends AbstractController
{
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }
    /**
     * @Route("/", name="welcome")
     */
    public function index()
    {
        return $this->render('welcome/index.html.twig',['logged_user'=>$this->session->get('logged_user')]);

        /*
    	$articles = $this->getDoctrine()->getRepository(Article::class)->getAllStories();

    	$article_list=array();

    	foreach($articles as $story)
    	{
    		$t=$story['added_at'];
    		if($t==null)
    		{
    			$message="Unpublished";
    		}
    		else
    		{
                $diff=date_diff(date_create($t),date_create());
    			//$diff=date_diff(date_create(get_object_vars($t)['date']),date_create());
    			if($diff->y>1)
                {
                    $message='Article published '.$diff->y." years ago";
                }
                else if($diff->y==1)
                {
                    $message='Article published '.$diff->y." year ago";
                }
                else
                {
                    $message='';
                }

                if($message=='')
                {
                    if($diff->m>1)
                    {
                        $message='Article published '.$diff->m." months ago";
                    }
                    else if($diff->m==1)
                    {
                        $message='Article published '.$diff->m." month ago";
                    }
                }

                if($message=='')
                {
                    if($diff->d>1)
                    {
                        $message='Article published '.$diff->d." days ago";
                    }
                    elseif($diff->d==1)
                    {
                        $message='Article published '.$diff->d." day ago";
                    }
                }

                if($message=='')
                {
                    if($diff->h>1)
                    {
                        $message='Article published '.$diff->h." hours ago";
                    }
                    elseif($diff->h==1)
                    {
                        $message='Article published '.$diff->h." hour ago";
                    }
                }

                if($message=='')
                {
                    if($diff->i>1)
                    {
                        $message='Article published '.$diff->i." minutes ago";
                    }
                    else
                    {
                        $message="Article published few minutes ago";
                    }
                }
    		}

            if($story['user_alias']!='')
            {
                $message.=', by '.$story['user_alias'];
            }
            else
            {
                if($story['user_name']!='')
                {
                    $message.=', by '.$story['user_name'];
                }
                else
                {
                    $message.=', by unknown user';
                }
            }
    		$article_list[]=array('id'=>$story['id'], 'name'=>$story['name'], 'text'=>$story['text'], 'message'=>$message );
    	}

        //dump($session->get('logged_user_id') );
        return $this->render('welcome/index.html.twig',['article_list'=>$article_list, 'logged_user'=>$this->session->get('logged_user')]);
        */
    }

    /**
    * @Route("/loadFirstPage", name="loadFirstPage", methods="POST")
    */
    public function FirstPage()
    {
        $count = $this->getDoctrine()->getRepository(Article::class)->CountArticles();
        return new JsonResponse(['total'=>$count['tot']]);
    }

    /**
    * @Route("/loadPage", name="loadPage", methods="POST")
    */
    public function LoadPage()
    {
        $articles = $this->getDoctrine()->getRepository(Article::class)->GetArticles($_POST['fid'], $_POST['lid'], $_POST['perPage'], $_POST['dir']);

        $i=0;
        foreach($articles as $story)
        {
            $message='';//reset it
            $t=$story['added_at'];
            if($t==null)
            {
                $message="Unpublished";
            }
            else
            {
                $message=$this->CalculatePublicationDate($t);
            }
            $articles[$i]['message']=$message;
            $i++;
        }

        return new JsonResponse(['articles'=>$articles]);
    }

    public function CalculatePublicationDate($t)
    {
        $diff=date_diff(date_create($t),date_create());
        if($diff->y>1)
        {
            $message='Article published '.$diff->y." years ago";
        }
        else if($diff->y==1)
        {
            $message='Article published '.$diff->y." year ago";
        }
        else
        {
            $message='';
        }

        if($message=='')
        {
            if($diff->m>1)
            {
                $message='Article published '.$diff->m." months ago";
            }
            else if($diff->m==1)
            {
                $message='Article published '.$diff->m." month ago";
            }
        }

        if($message=='')
        {
            if($diff->d>1)
            {
                $message='Article published '.$diff->d." days ago";
            }
            elseif($diff->d==1)
            {
                $message='Article published '.$diff->d." day ago";
            }
        }

        if($message=='')
        {
            if($diff->h>1)
            {
                $message='Article published '.$diff->h." hours ago";
            }
            elseif($diff->h==1)
            {
                $message='Article published '.$diff->h." hour ago";
            }
        }

        if($message=='')
        {
            if($diff->i>1)
            {
                $message='Article published '.$diff->i." minutes ago";
            }
            else
            {
                $message="Article published few minutes ago";
            }
        }
        return $message;
    }
}
