<?php

namespace App\Controller\Front;

use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class ShowController extends AbstractController
{
    /**
     * @Route("/profile/edit/{slug}", name="user_edit_profile", methods={"GET", "POST"})
     */
    public function editUserProfile(EntityManagerInterface $entityManager, Request $request, SluggerInterface $slugger): Response
    {

        //! ne pas s'inquièter de ce qui est souligné en rouge il n'y a pas de problème, tout fonctionne...mais ce serait bien de comprendre !
       
        // get user from session
        $user = $this->getUser();

            // if no user authenticated as advertiser, we create a new one
            if (!$user)
            {
                $this->addFlash('unautorized-access', "Oups ! Vous n'êtes pas autorisé à accèder à cette page !");
                $this->redirectToRoute('main_home', [], Response::HTTP_MOVED_PERMANENTLY);
            }

            // get userForm and bind the authenticated user
            $form = $this->createForm(UserType::class, $user);
            $form->handleRequest($request);

        
        if ($form->isSubmitted() && $form->isValid())
        { 

            /** @var UploadedFile 
             **/

            if ($form->get('avatar')->getData() != null) {
                
                $avatarFile = $form->get('avatar')->getData();

                $user->setAvatarFile($avatarFile);
                                 
                if ($avatarFile) {
                    $originalFilename = pathinfo($avatarFile->getClientOriginalName(), PATHINFO_FILENAME);

                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename = $safeFilename.'-'.uniqid().'.'.$avatarFile->guessExtension();
    
                    try {
                        $avatarFile->move(
                            $this->getParameter('user_avatar'),
                            $newFilename
                        );                 

                    } catch (FileException $e) {
                        // ... gérer les exeptions si problème d'upload en fonction des restrictions qu'on a pu donner dans le form
                    }
    
                    $user->setAvatar($newFilename);
                   
                }

               }

               if ($form->get('banner')->getData() != null) {

                $bannerFile = $form->get('banner')->getData();

                $user->setBannerFile($bannerFile);

                if ($bannerFile) {
                    $originalFilename = pathinfo($bannerFile->getClientOriginalName(), PATHINFO_FILENAME);

                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename = $safeFilename.'-'.uniqid().'.'.$bannerFile->guessExtension();

                    try {
                        $bannerFile->move(
                            $this->getParameter('user_banner'),
                            $newFilename
                        );
                    } catch (FileException $e) {
                        // ... gérer les exeptions si problème d'upload en fonction des restrictions qu'on a pu donner dans le form
                    }
    
                    $user->setBanner($newFilename);
                    
                } 

               }
            
                    // Flash message display a success message in user profil template
                    $currentUser = $user->getName();
                    $this->addFlash('success-user-edit', 'Félicitation ' . $currentUser . ' votre profil a été mis à jour !');

                    //dd($user); 

                    $entityManager->persist($user);
                    $entityManager->flush();
            //return user on his own profil page by slug   
            return $this->redirectToRoute('show_user_page', ['slug'=> $user->getSlug()], Response::HTTP_SEE_OTHER);
        }

            return $this->renderForm('front/form/edit_user_profile.html.twig', compact('form'));

    }

     /**
     * 
     * @Route("/user/{slug}", name="show_user_page")
     */
    public function showPlacePanel(UserRepository $userRepository,  string $slug): Response
    {
        //todo gérer la page du user pour y mettre ses favoris ou autre par exemple.
      
        // display user page
        $user = $userRepository->findOneBy(["slug" => $slug]);

        // keep User id
        $userId = $user->getId();

        return $this->render('front/main/user_profile.html.twig', compact('user'));
    }
}
