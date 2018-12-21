<?php

namespace App\Controller;

use App\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function addArticle(){
        //pour pouvoir sauvegarder un objet, on utilise l'entity manager
        $entityManager = $this->getDoctrine()->getManager();
        //on crée notre objet article en dur pour l'instant
        $article = new Article();
        $article->setTitle('Mon premier article');
        $article->setContent('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.');
        $article->setDatePubli(new \DateTime(date('Y-m-d H:i:s')));
        $article->setAuthor('Jean Lalanne');

        //Pour indiquer à doctrine de conserver l'obj, on doit le persister.
        $entityManager->persist($article);
        //Pour exécuter les requêtes sql
        $entityManager->flush();

        return $this->render('article/add.html.twig');

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

}
