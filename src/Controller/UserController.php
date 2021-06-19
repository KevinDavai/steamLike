<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EditPseudoType;
use App\Form\EditProfileType;
use App\Form\EditPasswordType;
use App\Form\EditFirstNameType;
use App\Security\EmailVerifier;


use App\Form\EditEmailProfileType;
use App\Repository\UserRepository;
use Symfony\Component\Mime\Address;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class UserController extends AbstractController
{
  private $emailVerifier;

  public function __construct(EmailVerifier $emailVerifier)
  {
      $this->emailVerifier = $emailVerifier;
  }

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
  public function ajax_noReloadUrl(Request $request, UserPasswordEncoderInterface $encoder, EntityManagerInterface $manager)
  {
    $user = $this->getUser();
    $name = $this->getUser()->getUsername();
    $formName = $this->createForm(EditProfileType::class, $user);

    $formName->handleRequest($request);

    if ($formName->isSubmitted() && !$formName->isValid()) {
      $response = new Response(json_encode(array(
        'status' => 'error',
        'errors' => $this->getErrorMessages($formName)
      )));

      $response->headers->set('Content-Type', 'application/json');

      $user->setUsername($name);

      return $response;
    }

    if ($formName->isSubmitted() && $formName->isValid()) {

      $em = $this->getDoctrine()->getManager();
      $em->persist($user);
      $em->flush();

      return $this->redirect('/account/general');
    }

    $email = $this->getUser()->getEmail();

    $FormEmail = $this->createForm(EditEmailProfileType::class, $user);

    $FormEmail->handleRequest($request);

    if ($FormEmail->isSubmitted() && !$FormEmail->isValid()) {
      $response = new Response(json_encode(array(
        'status' => 'error',
        'errors' => $this->getErrorMessages($FormEmail)
      )));

      $response->headers->set('Content-Type', 'application/json');

      $user->setEmail($email);

      return $response;
    }

    if ($FormEmail->isSubmitted() && $FormEmail->isValid()) {
      $user->setIsVerified(false);
      $em = $this->getDoctrine()->getManager();
      $em->persist($user);
      $em->flush();

      $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
      (new TemplatedEmail())
          ->from(new Address('claragmtt@gmail.com', 'Acme Mail Bot'))
          ->to($user->getEmail())
          ->subject('Please Confirm your Email')
          ->htmlTemplate('security/confirmation_email.html.twig')
      );

      return $this->redirect('/account/general');
    }

    $formPrenom = $this->createForm(EditFirstNameType::class, $user);

    $formPrenom->handleRequest($request);

    if ($formPrenom->isSubmitted() && !$formPrenom->isValid()) {
      $response = new Response(json_encode(array(
        'status' => 'error',
        'errors' => $this->getErrorMessages($formPrenom)
      )));

      $response->headers->set('Content-Type', 'application/json');

      $firstName = $this->getUser()->getFirstname();
      $nameReal = $this->getUser()->getName();

      $user->setFirstname($firstName);
      $user->setName($nameReal);

      return $response;
    }

    if ($formPrenom->isSubmitted() && $formPrenom->isValid()) {
      $em = $this->getDoctrine()->getManager();
      $em->persist($user);
      $em->flush();

      return $this->redirect('/account/general');
    }

    $formPassword = $this->createForm(EditPasswordType::class, $user);
    $formPassword->handleRequest($request);

    if ($formPassword->isSubmitted() && !$formPassword->isValid()) {
      $response = new Response(json_encode(array(
        'status' => 'error',
        'errors' => $this->getErrorMessages($formPassword)
      )));

      $response->headers->set('Content-Type', 'application/json');

      $pw = $this->getUser()->getPassword();
      $user->setPassword($pw);

      return $response;
    }

    if ($formPassword->isSubmitted() && $formPassword->isValid()) {
      $hash = $encoder->encodePassword($user, $user->getPassword());

      $user->setPassword($hash);

      $manager->persist($user);
      $manager->flush();

      return $this->redirect('/account/password');
    }

    if (isset($_GET['call_type'])) {
      $call_type = $request->query->get('call_type');

      if ($call_type == "/account/password") {

        $template = $this->render('/user/password.html.twig', [
          'user' => $user,
          'formPassword' => $formPassword->createView(),
        ])->getContent();

        $response = new Response(json_encode(array(
          'status' => 'success',
          'template' => $template,
          'formPassword' => $formPassword,
          'user' => $user,
        )));

        $response->headers->set('Content-Type', 'application/json');

        return $response;
      } else if ($call_type == "/account/general") {
        $template = $this->render('user/general.html.twig', [
          'user' => $user,
          'formEmail' => $FormEmail->createView(),
          'form' => $formName->createView(),
          'formPrenom' => $formPrenom->createView(),
        ])->getContent();

        $response = new Response(json_encode(array(
          'status' => 'success',
          'template' => $template,
          'formEmail' => $FormEmail,
          'form' => $formName,
          'formPrenom' => $formPrenom,
          'user' => $user,
        )));

        $response->headers->set('Content-Type', 'application/json');

        return $response;
      }
    }
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
