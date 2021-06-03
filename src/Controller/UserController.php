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

      $user = $this->getUser();
      $form = $this->createForm(EditProfileType::class, $user);
      $form->handleRequest($request);

      if($form->isSubmitted() && $form->isValid()) {
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
        }

        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
            'user' => $userLoged,
            'form' => $form->createView()
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

      $user = $this->getUser();
      $formName = $this->createForm(EditProfileType::class, $user);


      if ($request->isMethod('POST')) {
        $formName->submit($request->request->get($formName->getName()));
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
    }
}
