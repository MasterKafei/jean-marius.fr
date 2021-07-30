<?php

namespace App\Business;

use App\Entity\File;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\Response;

class FileBusiness 
{
    const UPLOAD_DIRECTORY = '/upload/';

    private TokenBusiness $tokenBusiness;

    private ParameterBagInterface $parameterBag;

    private EntityManagerInterface $entityManager;

    /** @required */
    public function setTokenBusiness(TokenBusiness $tokenBusiness): self
    {
        $this->tokenBusiness = $tokenBusiness;

        return $this;
    }

    /** @required */
    public function setParameterBag(ParameterBagInterface $parameterBag): self
    {
        $this->parameterBag = $parameterBag;

        return $this;
    }

    /** @required */
    public function setEntityManager(EntityManagerInterface $entityManager): self
    {
        $this->entityManager = $entityManager;

        return $this;
    }

    public function uploadFile(UploadedFile $uploadedFile, bool $persist = true)
    {
        $file = new File();
        
        $file
            ->setGeneratedName($this->tokenBusiness->generateRandomToken())
            ->setOriginalName($uploadedFile->getClientOriginalName())
        ;

        $uploadedFile->move($this->parameterBag->get('kernel.project_dir') . self::UPLOAD_DIRECTORY, $file->getGeneratedName());

        if ($persist) {
            $this->entityManager->persist($file);
            $this->entityManager->flush();
        }

        return $file;
    }

    public function downloadFile(File $file): Response
    {
        $name = $file->getOriginalName();
        $path = $this->parameterBag->get('kernel.project_dir') . self::UPLOAD_DIRECTORY . $file->getGeneratedName();

        $response = new BinaryFileResponse($path);
        $disposition = HeaderUtils::makeDisposition(
            HeaderUtils::DISPOSITION_ATTACHMENT,
            $name
        );

        $response->headers->set('Content-Disposition', $disposition);

        return $response;
    }
}