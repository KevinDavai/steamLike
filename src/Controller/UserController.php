<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EditPseudoType;
use App\Form\EditProfileType;
use App\Repository\UserRepository;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class UserController extends AbstractController
{

    #[Route('/account/general', name: 'account_general')]
      public function index(Request $request): Response
    {
      $repository = $this->getDoctrine()->getRepository(User::class);
      $userLoged = $repository->find($this->getUser()->getId());
    
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
            'user' => $userLoged,
        ]);
    }
    

    #[Route('/account/password', name: 'account_password')]
    public function change_password(): Response
    {
      $user = $this->getUser();
      return $this->render('user/index.html.twig', [
        'user' => $user,
        'controller_name' => 'UserController',
        ]);
    }

    /**
     * @Route("/ajax/redirect", name="ajax_redirect")
     */
    public function ajax_noReloadUrl(Request $request)
    {
      $user = $this->getUser();
      $name = $this->getUser()->getUsername();
      $formName = $this->createForm(EditProfileType::class, $user);

      $formName->handleRequest($request);

      if($formName->isSubmitted() && !$formName->isValid()) {
        $response = new Response(json_encode(array(
          'status'=>'error',
          'errors' => $this->getErrorMessages($formName)
        )));
        
        $response->headers->set('Content-Type', 'application/json');

        $user->setUsername($name);

        return $response;
      }
      
      if($formName->isSubmitted() && $formName->isValid()) {
        $em = $this->getDoctrine()->getManager();            
        $em->persist($user);
        $em->flush();
        
        return $this->redirect('/account/general');
      }

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
          $template = $this->render('user/general.html.twig', [
            'form' => $formName->createView(),
          ])->getContent();

          $response = new Response(json_encode(array(
            'status'=>'success',
            'template' => $template,
            'form' => $formName,
          )));

          $response->headers->set('Content-Type', 'application/json');
          
          return $response;
        }
      }
      
      return $this->render('user/index.html.twig', [
        'user' => $user,
        'form' => $formName->createView()   
      ]);
    }


        // Generate an array contains a key -> value with the errors where the key is the name of the form field
        protected function getErrorMessages(\Symfony\Component\Form\Form $form) 
        {
            $errors = array();
    
            foreach ($form->getErrors() as $key => $error) {
                $errors[] = $error->getMessage();
            }
    
            foreach ($form->all() as $child) {
                if (!$child->isValid()) {
                    $errors[$child->getName()] = $this->getErrorMessages($child);
                }
            }
    
            return $errors;
        }
}
