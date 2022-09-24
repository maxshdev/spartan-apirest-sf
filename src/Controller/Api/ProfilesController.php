<?php

namespace App\Controller\Api;

use App\Entity\Profile;
use App\Form\Model\ProfileDto;
use App\Form\Type\ProfileFormType;
use App\Repository\ProfileRepository;

use App\Service\FileUploader;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations\{Get, Post};
use FOS\RestBundle\Controller\Annotations\View as ViewAttribute;

class ProfilesController extends AbstractFOSRestController
{

    #[Get(path: "/profiles")]
    #[ViewAttribute(serializerGroups: ["profile"], serializerEnableMaxDepthChecks: true)]
    public function getAction(ProfileRepository $profileRepository) 
    {
        return $profileRepository->findAll();
    }

    #[Post(path: "/profiles")]
    #[ViewAttribute(serializerGroups: ["profile"], serializerEnableMaxDepthChecks: true)]
    public function postAction(EntityManagerInterface $em, FileUploader $fileUploader, Request $request) 
    {
        $entityDto = new ProfileDto();
        $form = $this->createForm(ProfileFormType::class, $entityDto);
        
        $form->handleRequest($request);
        
        if (!$form->isSubmitted()) {
            return new Response('', Response::HTTP_BAD_REQUEST);
        }

        if ($form->isSubmitted() && $form->isValid()) { 
            
            $entity = new Profile();
            $entity->setFirstname( $entityDto->firstname );
            $entity->setLastname( $entityDto->lastname );
            $entity->setAddress( $entityDto->address );
            $entity->setCountry( $entityDto->country );
            $entity->setLocality( $entityDto->locality );
            
            if ($entityDto->base64Image) {
                $filename = $fileUploader->uploadBase64File( $entityDto->base64Image );
                $entity->setImage( $filename );
            }

            $em->persist($entity);
            $em->flush();

            return $entity;
        }

        return $form;
    }
}