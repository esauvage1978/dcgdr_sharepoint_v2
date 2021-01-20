<?php

namespace App\Listener;

use App\Entity\Picture;
use App\Helper\FileTools;
use App\Service\Uploader;
use App\Helper\ImageResize;
use Doctrine\ORM\Mapping as ORM;

class PictureUploadListener
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
     * @var ImageResize
     */
    private $imageResize;

    public function __construct(ImageResize $ir, Uploader $uploader, string $directory)
    {
        $this->uploader = $uploader;
        $this->directory = $directory;
        $this->imageResize=$ir;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function prePersistHandler(Picture $picture)
    {
        if (!empty($picture->getFile())) {


            $extension = $this->uploader->getExtension($picture->getFile());

            if (empty($picture->getFileName())) {
                $picture->setFileName(md5(uniqid()));
            } else {
                $fileTools = new FileTools();
                $targetDir = $this->directory;
                $fileTools->remove($targetDir, $picture->getFullName());
            }

            if (empty($picture->getName())) {
                $picture->setName('Nouveau fichier');
            }

            $picture->setFileExtension($extension);
        }
        $picture->setUpdatedAt(new \DateTime());
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function postPersistHandler(Picture $picture)
    {
        if (!empty($picture->getFile())) {

            $targetDir = $this->directory;

            $this->uploader->setTargetDir($targetDir);
            $this->uploader->upload($picture->getFile(), $picture->getFileName());

            $this->imageResize->resize($targetDir.'/'. $picture->getFullName());
        }
    }

    /**
     * @ORM\PostRemove()
     */
    public function postRemoveHandler(Picture $picture)
    {
        $fileTools = new FileTools();
        $targetDir = $this->directory;
        $fileTools->remove($targetDir, $picture->getFullName());
    }
}
