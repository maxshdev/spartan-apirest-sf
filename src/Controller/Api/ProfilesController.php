<?php

namespace App\Controller\Api;

use App\Entity\Profile;
use App\Form\Model\ProfileDto;
use App\Repository\ProfileRepository;

use App\Form\Type\ProfileFormType;

use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations\{Get, Post};
use FOS\RestBundle\Controller\Annotations\View as ViewAttribute;
use League\Flysystem\FilesystemOperator;

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
    public function postAction(EntityManagerInterface $em, FilesystemOperator $defaultStorage, Request $request) 
    {
        $entityDto = new ProfileDto();
        $form = $this->createForm(ProfileFormType::class, $entityDto);
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) { 
            
            $extension = explode('/', mime_content_type($entityDto->base64Image))[1];
            $data = explode(',', $entityDto->base64Image);
            $filename = sprintf('%s.%s', uniqid('profile_', true), $extension);
            $defaultStorage->write($filename, base64_decode($data[1]));

            $entity = new Profile();
            $entity->setFirstname( $entityDto->firstname );
            $entity->setLastname( $entityDto->lastname );
            $entity->setAddress( $entityDto->address );
            $entity->setCountry( $entityDto->country );
            $entity->setLocality( $entityDto->locality );
            $entity->setImage( $filename );

            $em->persist($entity);
            $em->flush();

            return $entity;
        }

        return $form;
    }
}