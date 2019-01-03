<?php

namespace App\Controller;

use App\Entity\Message;
use App\Form\MessageType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MessageController extends AbstractController
{
    /**
     * @Route("/message", name="message")
     */
    public function index()
    {
        $repository = $this->getDoctrine()->getRepository(Message::class);
        $messages = $repository->findAll();
        return $this->render('message/index.html.twig', [
            'messages' => $messages,
        ]);
    }

    /**
     * @Route("/message/new", name="newMessage")
     */
    public function newMessage(Request $request){
        $entityManager = $this->getDoctrine()->getManager();
        $message = new Message();

        $form = $this->createForm(MessageType::class);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $message = $form->getData();
            $message->setDateenvoi(new \DateTime(date('Y-m-d H:i:s')));
            $entityManager->persist($message);
            $entityManager->flush();
            $this->addFlash('success', 'message envoyé');
            return $this->redirectToRoute('message');
        }

        return $this->render('message/new.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("message/{id}", name="messageId", requirements={"id"="[0-9]+"})
     */

    public function search($id){
        $repository = $this->getDoctrine()->getRepository(Message::class);
        $message = $repository->find($id);
        if(!$message){
            throw $this->createNotFoundException('Pas de message trouvé!');
        }
        return $this->render("message/message.html.twig", [ 'message' => $message]);
    }
}
