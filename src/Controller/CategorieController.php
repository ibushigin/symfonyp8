<?php

namespace App\Controller;

use App\Entity\Categorie;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CategorieController extends AbstractController
{
    /**
     * @Route("/categorie", name="categorie")
     */
    public function index()
    {
        $repository = $this->getDoctrine()->getRepository(Categorie::class);
        $categories = $repository->findAll();
        return $this->render('categorie/index.html.twig', ['categories'=>$categories]);
    }
    /**
     * @Route("/categorie/add", name="addCategorie")
     */
    public function addCategorie(){
        $entityManager = $this->getDoctrine()->getManager();
        $categorie = new Categorie();
        $categorie->setLibelle('astronomie');
        $categorie->setDescription('Article parlant d\'astronomie');
        $categorie->setDateCreation(new \DateTime(date('Y-m-d')));

        $entityManager->persist($categorie);
        $entityManager->flush();

        return $this->render('categorie/add.html.twig');


    }
    /**
     * @Route("/categorie/lastfive", name="lastFive")
     */
    public function showLastFive(){
        $repository = $this->getDoctrine()->getRepository(Categorie::class);
        $categories = $repository->findLastFive();
        return $this->render('categorie/lastfive.html.twig', ['categories' => $categories]);
    }
    /**
     * @Route("/categorie/{id}", name="categorieID", requirements={"id"="[0-9]+"})
     */
    public function search($id){

        $repository = $this->getDoctrine()->getRepository(Categorie::class);
        $categorie = $repository->find($id);

        //génération d'erreur si aucun article n'est trouvé
        if(!$categorie){
            throw $this->createNotFoundException('Pas de catégorie trouvée');
        }

        return $this->render('index.html.twig', [
            'categorie' => $categorie,
        ]);
    }
    /**
     * @Route("categorie/update/{id}", name="categorieArticle", requirements={"id"="\d+"})
     */

    public function updateCategorie($id){
        $repository = $this->getDoctrine()->getRepository(Categorie::class);
        $categorie = $repository->find($id);
        if(!$categorie){
            throw $this->createNotFoundException('no categorie found');
        }
        $categorie->setContent('contenu modifié');
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();

        $this->addFlash('success', 'catégorie modifiée');

        return $this->redirectToRoute('categorieID', ['id' => $categorie->getId()]);

    }
    /**
     * @Route("/categorie/delete/{id}", name="deleteCategorie", requirements={"id"="\d+"})
     */

    public function deleteCategorie(Categorie $categorie){
        //Récupération de l'entity manager nécessaire pour la suppression
        $entityManager = $this->getDoctrine()->getManager();
        //je veux supprimer cet article
        $entityManager->remove($categorie);
        //pour valider la suppression
        $entityManager->flush();
        //génération d'un message flash
        $this->addFlash('warning', 'Catégorie supprimée');
        //redirection vers la liste des articles
        return $this->redirectToRoute('categorie');
    }

}
