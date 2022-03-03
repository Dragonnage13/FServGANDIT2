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
            $repons ="Utilisateur inconnu, veuillez reessayer !";
             } 
        else{
             $code = $reponse -> getPassword();
             if (password_verify($password,$code)){
                 $repons = "Acces autorisé";
             }else {
                $repons = "MOT DE PASSE INVALIDE !";
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
        $nom = $request -> request -> get("nom");
        $Prenom = $request -> request -> get("Prenom");
        $MDP = $request -> request -> get("MDP");
        $MDP = (password_hash($MDP, PASSWORD_DEFAULT));
        $newUti->setLogin($nom);
        $newUti->setPassword($MDP);
        $manager->persist($newUti);
        $manager->flush();

        $text = "Le nouvel utilisateur a été ajouté !";

       return $this->render('gandit/ajoututilisateur.html.twig', [
            'text' => $text,
        ]);
}



/**
     * @Route("/gandit/tableau", name="gandit/tableau")
     */
    public function tableau(EntityManagerInterface $manager): Response
    {
        $Utilisateurs=$manager->getRepository(Utilisateur::class)->findAll();
        return $this->render('gandit/tableau.html.twig',['listUtilisateurs' => $Utilisateurs]);
}
/**
     * @Route("/serveur/ouverture", name="gandit/ouverture")
     */
    public function ouverture(interfaceOuverture $ouverture): Response
    {
        $vs = $ouverture -> get('nomVar');
        $val=44;
        $ouverture -> set('nomVar',$val);
        return $this->render ('gandit/ouverture.html.twig', ['name' => $vs]);
}

/**
* @Route("/supprimerUtilisateur/{id}",name="supprimerUtilisateur")
*/
public function supprimerUtilisateur(EntityManagerInterface $manager,Utilisateur $editutil): Response {
    $manager->remove($editutil);
    $manager->flush();
    return $this->redirectToRoute ('gandit/tableau');
 }

}
