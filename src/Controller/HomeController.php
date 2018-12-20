<?php
//je range toutes mes classes de controleur dans le namespace App\Controller

namespace App\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


//pour pouvoir utiliser la méth render et autres
class HomeController extends AbstractController{
  /**
  *Grace aux annotations, je peux peux déclarer ma route
  *@Route ("/bonjour")
  *
  */
 public function bonjour(){
   return new Response('<html><body><strong>Bonjour</strong></body></html>');
 }

 /**
  * On peut control ece que va contenir le placeholder avec une regex
 * @Route ("/bonjour/{nom}", name="bonjourNom", requirements={"nom"="[a-z]+"})
 */
 public function bonjourPseudo($nom){
     return $this->render('bonjourPseudo.html.twig', array('pseudo'=> $nom));
 }


 /**
 *Grace aux annotations, je peux peux déclarer ma route
 *@Route ("/", name="Home")
 *
 */
 public function home(){
   $pseudo = 'Toto';
   return $this->render('index.html.twig', array('nom' => $pseudo));
 }

/**
 *@Route ("/exercice2/heure")
 */
public function heure(){
    $date = date("Y-m-d");
    $hour = date("H:i:s");
    return $this->render('exercice.html.twig', array('date'=>$date, 'hour'=>$hour));
}


/**
 * méthode qui va faire une redirect vers la page d'accueil
 * @Route("/redirect")
 */

public function redirectHome(){
    //Pour faire une redirect en param le nom de la route vers laquelle on veut rediriger
    return $this->redirectToRoute('home');
}
}
