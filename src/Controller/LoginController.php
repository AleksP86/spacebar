<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

use App\Entity\UserData;

class LoginController extends AbstractController
{
    
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }
    
    /**
     * @Route("/login", name="login")
     */
    public function index()
    {
        return $this->render('login/index.html.twig'
        	, ['controller_name' => 'LoginController', 'logged_user'=>$this->session->get('logged_user'), 'user_avatar'=>$this->session->get('user_avatar')]);
    }

    /**
    * @Route("/login_check", name="login_check", methods="POST")
    */
    public function loginCheck(/*EntityManagerInterface $em/*, Request $request/*, SessionInterface $session*/)
    {
        $check=$this->getDoctrine()->getRepository(UserData::class)->CheckUser($_POST['un'], $_POST['up']) ;
        //dump($check);

        //dump(sizeof($check));

        if(sizeof($check)>0)
        {
            $this->session->set('logged_user', $check[0]->getAlias());
            $this->session->set('logged_user_id', $check[0]->getId());
            $this->session->set('user_avatar', "/uploads/".$check[0]->getAvatar());

            /*
            dump($this->session->get('logged_user') );
            dump($this->session->get('user_avatar') );
            */
            //$session->set('logged_user_id',$check[0]->getId() );
            return new JsonResponse(true);
        }
        else
        {
            return new JsonResponse(false);
        }
    }

    /**
    * @Route("/registerCheck", name="register_check", methods="POST")
    */
    public function registerCheck()
    {
        $check_user=$this->getDoctrine()->getRepository(UserData::class)->CheckUserName($_POST['un']);
        //returns false if nothing found
        if($check_user===false)
        {
            //check alias

            //add user
            $adduser = $this->getDoctrine()->getRepository(UserData::class)->AddUser($_POST['un'],$_POST['up'],$_POST['al']);
            //check is adding executed correctly
            //no user, insert failed returns false
            if($adduser===false)
            {
                return new JsonResponse(['message'=>'Adding new user failed.']);
            }
            else
            {
                return new JsonResponse(['message'=>'New user created.']);
            }
        }
        else
        {
            //user with same username already exists
            return new JsonResponse(['message'=>'Username already taken.']);
        }
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function LogoutUser()
    {
        $this->session->remove('logged_user');
        $this->session->remove('logged_user_id');
        $this->session->remove('user_avatar');

        return $this->redirectToRoute('welcome');
    }
}
