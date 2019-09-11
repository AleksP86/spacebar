<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

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
        	, ['controller_name' => 'LoginController', 'logged_user'=>$this->session->get('logged_user')]);
    }

    /**
    * @Route("/login_check", name="login_check", methods="POST")
    */
    public function loginCheck(EntityManagerInterface $em, Request $request/*, SessionInterface $session*/)
    {
        $check=$this->getDoctrine()->getRepository(UserData::class)->CheckUser($_POST['un'], $_POST['up']) ;
        //dump($check);

        //dump(sizeof($check));

        if(sizeof($check)>0)
        {
            $this->session->set('logged_user', $check[0]->getAlias());
            $this->session->set('logged_user_id', $check[0]->getId());

            //dump($session->get('logged_user') );

            //$session->set('logged_user_id',$check[0]->getId() );
            return new JsonResponse(true);
        }
        else
        {
            return new JsonResponse(false);
        }
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function LogoutUser()
    {
        $this->session->remove('logged_user');
        $this->session->remove('logged_user_id');

        return $this->redirectToRoute('welcome');
    }
}
