<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Utilisateur;

class GANDITController extends AbstractController
{
    /**
     * @Route("/gandit", name="gandit")
     */
    public function index(): Response
    {
        return $this->render('gandit/index.html.twig', [
            'controller_name' => 'GANDITController',
        ]);
    }

    /**
     * @Route("/gandit/message", name="message")
     */
    public function message(Request $request,EntityManagerInterface $manager): Response
    {   
        $Login = $request -> request -> get("identifiant");
        $password = $request -> request -> get("password");
        $reponse = $manager -> getRepository(Utilisateur :: class) -> findOneBy([ 'login' => $Login]);
        if ($reponse == NULL){
            $repons ="Utilisateur inconnu";
             } 
        else{
             $code = $reponse -> getPassword();
             if ($code == $password){
                 $repons = "Acces autorisé";
             }else {
                $repons = "MOT DE PASSE INVALIDE";
             }
             
             }
        return $this->render('gandit/message.html.twig', [
            'message' => $repons,
        ]);
    }

    /**
     * @Route("/gandit/creerutilisateur", name="gandit/creerutilisateur")
     */
    public function creerutilisateur(): Response
    {
        return $this->render('gandit/creerutilisateur.html.twig', [
            'controller_name' => 'GanditController',
        ]);
}
/**
     * @Route("/gandit/ajoututilisateur", name="/gandit/ajoututilisateur")
     */
    public function ajoututilisateur(Request $request, EntityManagerInterface $manager): Response
    {
        $newUti = new Utilisateur();
        //$Nom = new Utilisateur();
        //$Prenom = new Utilisateur();
        //$MDP = new Utilisateur ();
        $nom = $request -> request -> get("nom");
        $Prenom = $request -> request -> get("Prenom");
        $MDP = $request -> request -> get("MDP");
        $newUti->setNom($nom);
        $manager->persist($newUti);
        $newUti->setPrenom($Prenom);
        $manager->persist($newUti);
        $newUti->setCode($MDP);
        $manager->persist($newUti);
        $manager->flush();

        $text = "ajout effectuer";

       return $this->render('gandit/ajoututilisateur.html.twig', [
            'text' => $text,
        ]);
}
}
