<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index()
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    /**
     * @Route("/test/deny")
     */
    public function testDeny(){
        //si l'utilisateur n'a pas le ROLE_AUTEUR, une erreur 403 est renvoyé
        $this->denyAccessUnlessGranted('ROLE_AUTEUR', null, 'page interdite!');
        //si on a le role_auteur, le reste du controleur est exécuté

        return $this->render('admin/index.html.twig', ['controller_name' => 'AdminController']);
    }

    //Autre méth pour restreindre l'accès à un controleur: les annotations
    /**
     * @Route("test/deny2")
     * @Security("has_role('ROLE_AUTEUR')")
     */

    public function testDeny2(){
        return $this->render('admin/index.html.twig', ['controller_name' => 'AdminController']);
    }
}
