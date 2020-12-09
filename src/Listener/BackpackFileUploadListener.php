<?php

namespace App\Listener;

use App\Entity\BackpackFile;
use App\Helper\FileDirectory;
use App\Helper\Slugger;
use App\Helper\SplitNameFile;
use App\Service\Uploader;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class BackpackFileUploadListener
{
    /**
     * @var Uploader
     */
    private $uploader;

    /**
     * @var string
     */
    private $directory;

    /**
     * @var SplitNameFile
     */
    private $splitNameFile;

    /**
     * @var FileDirectory
     */
    private $fileDirectory;

    public function __construct(Uploader $uploader, string $directory, SplitNameFile $splitNameFile, FileDirectory $fileDirectory)
    {
        $this->uploader = $uploader;
        $this->directory = $directory;
        $this->splitNameFile=$splitNameFile;
        $this->fileDirectory=$fileDirectory;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function prePersistHandler(BackpackFile $backpackFile)
    {

        $file=$backpackFile->getFile();
        if (!empty($file)) {

            $this->splitNameFile->split($file->getClientOriginalName());
            $extension = $this->splitNameFile->getExtension();

            if (empty($backpackFile->getFileName())) {
                $backpackFile->setFileName(Slugger::slugify($this->splitNameFile->getName()) );
            }
            if (empty($backpackFile->getTitle())) {
                $backpackFile->setTitle('Nouveau fichier');
            }

            $backpackFile->setFileExtension($extension);
            $backpackFile->setSize($this->uploader->getSize($file));
        }
        $backpackFile->setUpdatedAt(new \DateTime());
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function postPersistHandler(BackpackFile $backpackFile)
    {
        if (!empty($backpackFile->getFile())) {
            $fileDirectory = $this->fileDirectory;

            $fileDirectory->createDir($this->directory, $backpackFile->getBackpack()->getId());
            $targetDir = $this->directory.'/'.$backpackFile->getBackpack()->getId();

            if (null !== $backpackFile->getFullName()) {
                $fileDirectory->removeFile($targetDir, $backpackFile->getFullName());
            }

            $this->uploader->setTargetDir($targetDir);
            $this->uploader->upload($backpackFile->getFile(), $backpackFile->getFileName());
        }
    }

    /**
     * @ORM\PostRemove()
     */
    public function postRemoveHandler(BackpackFile $backpackFile)
    {
        $fileDirectory = $this->fileDirectory;
        $targetDir = $this->directory.'/'.$backpackFile->getBackpack()->getId();
        $fileDirectory->removeFile($targetDir, $backpackFile->getFullName());
    }
}
