<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
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
    //$request contient toutes les infos sur la requête http dont GET et POST
    public function addCategorie(Request $request){
        $entityManager = $this->getDoctrine()->getManager();
        $categorie = new Categorie();


        $form = $this->createForm(CategorieType::class);

        //je demande au form de gérer la requête
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            //le form a été soumis et validé
            //je crée un obj categorie à partir des données envoyées
            $categorie = $form->getData();
            $categorie->setDateCreation(new \DateTime(date('Y-m-d H:i:s')));

            $entityManager->persist($categorie);
            $entityManager->flush();
            $this->addFlash('success', 'catégorie ajoutée');
            return $this->redirectToRoute('categorie');
        }



        return $this->render('categorie/add.html.twig', ['form' => $form->createView()]);


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
     * @Route("categorie/update/{id}", name="updateCategorie", requirements={"id"="\d+"})
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
