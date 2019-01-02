<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    /**
     * Route qui va afficher la liste des articles
     * @Route("/articles", name="articles")
     */
    public function index()
    {
        //recup de la liste des articles
        $repository = $this->getDoctrine()->getRepository(Article::class);
        $articles = $repository->findAll();

        return $this->render('article/index.html.twig', [
            'articles' => $articles,
        ]);
    }
    /**
     * @Route("/article/add", name="addArticle")
     */
    public function addArticle(Request $request){
        //pour pouvoir sauvegarder un objet, on utilise l'entity manager
        $entityManager = $this->getDoctrine()->getManager();
        //on crée notre objet article en dur pour l'instant
        $article = new Article();

        $form = $this->createForm(ArticleType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $article = $form->getData();
            $entityManager->persist($article);
            $entityManager->flush();
            $this->addFlash('success', 'article ajouté!');
            return $this->redirectToRoute('articles');
        }

        return $this->render('article/add.html.twig', ['form' => $form->createView()]);

    }
    /**
     * @Route("/article/{id}", name="articleID", requirements={"id"="[0-9]+"})
     */
    public function search($id){
        //recup de la liste des articles
        $repository = $this->getDoctrine()->getRepository(Article::class);
        $article = $repository->find($id);

        //génération d'erreur si aucun article n'est trouvé
        if(!$article){
            throw $this->createNotFoundException('Pas d\'article trouvé');
        }

        return $this->render('article/article.html.twig', [
            'article' => $article,
        ]);
    }

    /**
     * @Route ("/article/recent", name="showRecentArticles")
     */
    public function showRecent(){
        $repository = $this->getDoctrine()->getRepository(Article::class);
        $articles = $repository->findAllPostedAfter("2000-01-01");

        $articles2 = $repository->findAllPostedAfterSansLesMains("2000-01-01");
        return $this->render('article/recent.html.twig', ['articles' => $articles, 'articles2' => $articles2]);
    }
    /**
     * @Route("article/update/{id}", name="updateArticle", requirements={"id"="\d+"})
     */


    public function updateArticle(Request $request, Article $article){
        //récupération du manager
        $entityManager = $this->getDoctrine()->getManager();

        //je crée mon formulaire, je lui passe en second paramètre mon objet catégorie afin qu'il pré-remplisse le formulaire
        $form = $this->createForm(ArticleType::class, $article);
        //je lui donne la requête
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            //si le formulaire a été envoyé et validé
            //on récupère l'objet catégorie
            $article = $form->getData();
            //enregistrement dans la base
            $entityManager->flush();
            $this->addFlash('success', 'article modifié');
            return $this->redirectToRoute('articles');
        }
        return $this->render('article/add.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/article/delete/{id}", name="deleteArticle", requirements={"id"="\d+"})
     */

    public function deleteArticle(Article $article){
        //Récupération de l'entity manager nécessaire pour la suppression
        $entityManager = $this->getDoctrine()->getManager();
        //je veux supprimer cet article
        $entityManager->remove($article);
        //pour valider la suppression
        $entityManager->flush();
        //génération d'un message flash
        $this->addFlash('warning', 'Article supprimé');
        //redirection vers la liste des articles
        return $this->redirectToRoute('articles');
    }


}
