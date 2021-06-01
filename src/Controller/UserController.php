<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;


class UserController extends AbstractController
{
    private $pageURL;

    #[Route('/account/general', name: 'account_general')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }
    

    #[Route('/account/password', name: 'account_password')]
    public function change_password(): Response
    {
      return $this->redirectToRoute('account_general'); // A revoir
    }

    /**
     * @Route("/ajax/redirect", name="ajax_redirect")
     */
    public function ajax_noReloadUrl(Request $request)
    {
      if(isset($_GET['call_type']))
      {
        $call_type = $request->query->get('call_type');   

        if($call_type == "/account/password")
        {
                  
          $template = $this->render('/user/password.html.twig')->getContent();

          $response = new Response(json_encode(array(
            'status'=>'success',
            'template' => $template,
          )));
          
          $response->headers->set('Content-Type', 'application/json');
          
          return $response;
        }
        else if($call_type == "/account/general")
        {
          $template = $this->render('/user/general.html.twig')->getContent();

          $response = new Response(json_encode(array(
            'status'=>'success',
            'template' => $template,
          )));

          $response->headers->set('Content-Type', 'application/json');
          
          return $response;
        }
      } 

    }



}
