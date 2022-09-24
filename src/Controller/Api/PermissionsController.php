<?php

namespace App\Controller\Api;

use App\Repository\PermissionRepository;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;

class PermissionsController extends AbstractFOSRestController
{

    /**
     * @Rest\Get(path="/permissions")
     * @Rest\View(serializerGroups={"permission"}, serializerEnableMaxDepthChecks=true)
     */
    public function getActions(PermissionRepository $permissionRepository) 
    {
        return $permissionRepository->findAll();
    }
}