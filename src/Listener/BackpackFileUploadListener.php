<?php

namespace App\Listener;

use App\Entity\BackpackFile;
use App\Helper\FileDirectory;
use App\Helper\Slugger;
use App\Helper\SplitFile;
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
     * @var SplitFile
     */
    private $splitFile;

    public function __construct(Uploader $uploader, string $directory)
    {
        $this->uploader = $uploader;
        $this->directory = $directory;
        $this->splitFile=new  SplitFile();
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function prePersistHandler(BackpackFile $backpackFile)
    {

        $file=$backpackFile->getFile();
        if (!empty($file)) {

            $this->splitFile->split($file->getClientOriginalName());
            $extension = $this->splitFile->getExtension();

            if (empty($backpackFile->getFileName())) {
                $backpackFile->setFileName(Slugger::slugify($this->splitFile->getName()) );
            }
            if (empty($backpackFile->getTitle())) {
                $backpackFile->setTitle('Nouveau fichier');
            }

            $backpackFile->setFileExtension($extension);
            $backpackFile->setSize($this->uploader->getSize($file));
        }
        $backpackFile->setUpdateAt(new \DateTime());
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function postPersistHandler(BackpackFile $backpackFile)
    {
        if (!empty($backpackFile->getFile())) {
            $fileDirectory = new FileDirectory();

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
        $fileDirectory = new FileDirectory();
        $targetDir = $this->directory.'/'.$backpackFile->getBackpack()->getId();
        $fileDirectory->removeFile($targetDir, $backpackFile->getFullName());
    }
}
