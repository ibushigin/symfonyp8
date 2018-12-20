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
 * @Route ("/bonjour/{nom}")
 */
 public function bonjourPseudo($nom){
     return $this->render('bonjourPseudo.html.twig', array('pseudo'=> $nom));
 }


 /**
 *Grace aux annotations, je peux peux déclarer ma route
 *@Route ("/")
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
}
