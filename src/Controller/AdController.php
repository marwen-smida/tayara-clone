<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AdType;
use App\Repository\AdRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdController extends AbstractController
{
    function console_log( $data ){
        echo '<script>';
        echo 'console.log('. json_encode( $data ) .')';
        echo '</script>';
    }



    /**
     * Show all ads
     * @Route("/ad/all", name="show_all")
     */
    public function index(AdRepository $repo): Response
    {
        $ads = $repo->findAll();

        return $this->render('ad/index.html.twig', [
            'controller_name' => 'AdController',
            'ads' => $ads
        ]);
    }

    /**
     *Create a new add
     * @Route("/ad/new", name="new_ad")
     *
     */

    public function createAd(Request $request, EntityManagerInterface $manager) : Response
    {
         $ad = new Ad();
         $form = $this->createForm(AdType::class, $ad);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){


//            $ad->initSlug();
            foreach($ad->getImages() as $image) {
                $image->setAd($ad);
                $manager->persist($image);
            }

            $ad->setAuthor($this->getUser());



            $manager->persist($ad);
                $manager->flush();
//                dump($ad);

                $this->addFlash(
                    'success',"L'annonce: {$ad->getTitle()} est bien créée"
                );
                return $this->redirectToRoute('show_ad',['slug' =>$ad->getSlug()]);


            }



        return $this->render('ad/new.html.twig', [
            'form' => $form->createView()
        ]);
    }






    /**
     * Show selected Ad
     *
     * @Route("/ad/{slug}", name="show_ad")
     * @return Response
     */
    public function show($slug, AdRepository $repo){

        $ad = $repo->findOneBySlug($slug);
        return $this->render('ad/show.html.twig', [
            'ad' => $ad
        ]);
    }


    /**
     * permet d'afficher le formulaire d'edition
     * @Route("/ad/{slug}/edit", name="edit_ad")
     * @return void
     */
    public function edit(Ad $ad, Request $request, EntityManagerInterface $manager){

        // on creer le formulaire avec le formbuilder
        $form = $this->createForm(AdType::class, $ad);
        // on recuperer la request du formulaire
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            // boucle enregistrement en base de données des images suplementaire
            foreach($ad->getImages() as $image) {
                $image->setAd($ad);
                $manager->persist($image);
            }

            // on demande au manager d'entité de persister et d'enregistrer en base de données
            $manager->persist($ad);
            $manager->flush();

            // on creer un message flash de success
            $this->addFlash(
                'success',
                "Les modifications de l'annonce on bien etait modifier !"
            );

            // on retourne une redirection
            return $this->redirectToRoute('show_ad', [
                // on passe le slug dans la redirection ce qui permet d'aller directement sur l'annonce creer
                'slug' => $ad->getSlug()
            ]);
        }


        return $this->render("ad/edit.html.twig",[
            'form' => $form->createView(),
            'ad' => $ad
        ]);
    }






}
