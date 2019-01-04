<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Categorie;
use App\Entity\Message;
use App\Entity\User;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        for($i=1; $i<= 50; $i++){
            //génération aléatoire de date
            $timestamp = mt_rand(1, time());
            $randomDate = date('Y-m-d H:i:s', $timestamp);
            //tableau d'auteurs
            $auteurs = ['Verlaine', 'Hugo', 'Voltaire', 'Philip K Dick', 'Zola', 'Dumas', 'Molière'];
            //génération de lorem
            $content = simplexml_load_file('http://www.lipsum.com/feed/xml?amount=1&what=paras&start=0')->lipsum;

            $user = new User();
            if($i===1){
                $roles = ['ROLE_ADMIN', 'ROLE_USER'];
            }else{
                $roles = ['ROLE_USER'];
            }
            $user->setUsername('user' .$i);
            $user->setEmail('user'.$i.'@gmail.com');
            $user->setRoles($roles);
            $plainPwd = 'mdp';
            $mdpEncoded = $this->encoder->encodePassword($user, $plainPwd);
            $user->setPassword($mdpEncoded);




            $categorie = new Categorie();
            $categorie->setLibelle('catégorie' . $i);
            $categorie->setDescription('description' . $i);
            $categorie->setDateCreation(new \DateTime($randomDate));

            $message = new Message();
            $message->setSujet('sujet' .$i);
            $message->setContenu($content);
            $message->setEmail('email' .$i);
            $message->setNom($auteurs[array_rand($auteurs)]);
            $message->setDateenvoi(new \DateTime($randomDate));

            $article = new Article();
            $article->setTitle('title' .$i);
            $article->setContent($content);
            $article->setAuthor($auteurs[array_rand($auteurs)]);
            $article->setDatePubli(new \DateTime($randomDate));



            $manager->persist($categorie);
            $manager->persist($message);
            $manager->persist($article);
            $manager->persist($user);
        }

        $manager->flush();
    }
}
