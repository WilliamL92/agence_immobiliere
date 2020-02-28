<?php

namespace App\Controller;

use App\Entity\Membres;
use App\Repository\MembresRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    /**
     * @Route("/profile", name="user_profile")
     */
    public function profile()
    {   

        return $this->render('user/profile.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    /**
     * @Route("profile/delete/{id}", name="user_delete")
     */
    public function delete(EntityManagerInterface $manager, MembresRepository $repository, int $id)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
         
        $membre = $this->getUser();

        $currentUserId = $membre->getId();
        if ($currentUserId == $id)
        {
            $session = $this->get('session');
            $session = new Session();
            $session->invalidate();
        } 

        $manager->remove($membre);
        $manager->flush();

        return $this->redirectToRoute('home');
    }
}
