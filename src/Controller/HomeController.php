<?php
//je range toutes mes classes de controleur dans le namespace App\Controller

namespace App\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController{
  /**
  *Grace aux annotations, je peux peux déclarer ma route
  *@Route ("/bonjour")
  *
  */
 public function bonjour(){
   return new Response('<html><body><strong>Bonjour</strong></body></html>');
 }
}
