<?php

namespace App\Controller;

use App\Form\BienImmoType;
use App\Entity\BiensImmobilier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class AgenceImmobiliereController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        return $this->render('agence_immobiliere/index.html.twig', [
            'controller_name' => 'AgenceImmobiliereController',
        ]);
    }

    /**
     * @Route("/biens_immobiliers", name="biens_immobiliers")
     */

    public function biens_immobiliers(Request $request, EntityManagerInterface $manager){
        $repo = $this->getDoctrine()->getRepository(BiensImmobilier::class);
        $biens_immo = $repo->findAll();

        $biens = new BiensImmobilier();
        $form_biens = $this->createForm(BienImmoType::class, $biens);
        $form_biens->handleRequest($request);

        if($form_biens->isSubmitted() && $form_biens->isValid()){

                // upload de l'image
            $image_file = $form_biens->get('images')->getData();
            $original_image_name = pathinfo($image_file->getClientOriginalName(), PATHINFO_FILENAME);
            
                    // this is needed to safely include the file name as part of the URL
                    $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $original_image_name);
                    $newFilename = $safeFilename.'-'.uniqid().'.'.$image_file->guessExtension();
                    $image_file->move(
                        $this->getParameter('images_directory'),
                        $newFilename);
                        $biens->setImages($newFilename);


            $now = new \DateTime();
            $biens->setDateDeCreation($now);

            $manager->persist($biens);
            $manager->flush();
            return $this->redirectToRoute('biens_immobiliers');
        }

        return $this->render('agence_immobiliere/biens_immobiliers.html.twig', ["biens_immo" => $biens_immo, "form_biens" => $form_biens->createView()]);
    }

}
