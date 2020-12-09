<?php

namespace App\Helper;

use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @author Emmanuel SAUVAGE <emmanuel.sauvage@live.fr>
 * @version 1.0.0
 */
class FileDirectory
{
    /**
     * @var Filesystem
     */
    private $fsObject;

    /**
     * @var SplitNameFile
     */
    private $splitNameFile;


    public function __construct(SplitNameFile $splitNameFile)
    {
        $this->fsObject = new Filesystem();
        $this->splitNameFile=$splitNameFile;
    }

    public function dirExist(string $chemin, string $directory)
    {
        $new_dir_path = $chemin . "/" . $directory;
        return $this->fsObject->exists($new_dir_path);
    }

    public function createDir(string $chemin, string $directory)
    {

        $new_dir_path = $chemin . "/" . $directory;
        if (!$this->fsObject->exists($new_dir_path)) {
            try {
                $old = umask(0);
                $this->fsObject->mkdir($new_dir_path, 0775);
                umask($old);
            } catch (\Exception $e) {
                dump($e->getMessage());
            }
        }

    }

    public function removeFile(string $chemin, string $file)
    {
        try {
            $fullpath = $chemin . "/" . $file;
            if ($this->fsObject->exists($fullpath)) {
                $this->fsObject->remove($fullpath);
            }
        } catch (IOExceptionInterface $exception) {
            echo "Error creating directory at" . $exception->getPath();
        }
    }

    public function moveFile(string $cheminSource, string $fileSource, string $cheminDestination, string $fileDestination)
    {
        try {
            $fullpathSource = $this->fullPathSource($cheminSource, $fileSource);
            $fullpathDestination = $cheminDestination . "/" . $fileDestination;

            if ($this->fsObject->exists($fullpathSource)) {
                $this->removeFile($cheminDestination, $fileDestination);
                $this->fsObject->copy($fullpathSource, $fullpathDestination);
            } else {
                dump('file not exist ' . $fullpathSource);
            }
        } catch (IOExceptionInterface $exception) {
            echo "Error creating directory at" . $exception->getPath();
        }
    }

    public function fileExist(string $cheminSource, string $fileSource)
    {
        return $this->fsObject->exists($this->fullPathSource($cheminSource, $fileSource));
    }

    public function fullPathSource(string $cheminSource, string $fileSource)
    {
        return $cheminSource . "/" . $fileSource;
    }

    public function fileSize(string $cheminSource, string $fileSource)
    {
        if ($this->fileExist($cheminSource, $fileSource)) {
            return filesize($this->fullPathSource($cheminSource, $fileSource));
        }
        return 0;
    }

    public function toSlugAllFiles(string $cheminSource)
    {
        $sf= $this->splitNameFile;
        $dir = opendir($cheminSource);

        while ($file = readdir($dir)) {
            if (is_file($cheminSource . DIRECTORY_SEPARATOR . $file)) {
                $sf->split($file);
                $slugified = Slugger::slugify($sf->getName());
                if ($file != $slugified .'.'.$sf->getExtension()) {
                    rename(
                        $cheminSource . DIRECTORY_SEPARATOR . $file,
                        $cheminSource . DIRECTORY_SEPARATOR . $slugified .'.'. $sf->getExtension());
                }
            }
        }
    }
}