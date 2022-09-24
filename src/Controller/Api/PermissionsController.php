<?php

namespace App\Controller\Api;

use App\Entity\Permission;
use App\Repository\PermissionRepository;

use App\Form\Type\PermissionFormType;

use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;

class PermissionsController extends AbstractFOSRestController
{

    /**
     * @Rest\Get(path="/permissions")
     * @Rest\View(serializerGroups={"permission"}, serializerEnableMaxDepthChecks=true)
     */
    public function getAction(PermissionRepository $permissionRepository) 
    {
        return $permissionRepository->findAll();
    }

    /**
     * @Rest\Post(path="/permissions")
     * @Rest\View(serializerGroups={"permission"}, serializerEnableMaxDepthChecks=true)
     */
    public function postAction(EntityManagerInterface $em, Request $request) 
    {
        $entity = new Permission();
        $form = $this->createForm(PermissionFormType::class, $entity);
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) { 
            $em->persist($entity);
            $em->flush();

            return $entity;
        }

        return $form;
    }
}