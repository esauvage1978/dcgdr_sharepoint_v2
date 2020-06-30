<?php

namespace App\Helper;

/**
 * @author Emmanuel SAUVAGE <emmanuel.sauvage@live.fr>
 * @version 1.0.0
 */
class ConvertFileJsonToArray
{
    /**
     * @var string
     */
    private $directory;

    /**
     * @var string
     */
    private $fileName;

    public function __construct()
    {

    }

    /**
     * @param string $directory
     */
    public function  setDirectory(string $directory)
    {
        $this->directory=$directory;
    }

    /**
     * @param string $fileName
     */
    public function  setFileSource(string $fileName)
    {
        $this->fileName=$fileName;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        if (!$this->checkFile()) {
            throw new \InvalidArgumentException('Le fichier '. $this->fileName .' n\'existe pas dans le répertoire ' . $this->directory);
        }

        $data= json_decode(
            $this->readJson(),
            true
        );

        if($data===null) {
            throw new \InvalidArgumentException('Le fichier '. $this->fileName .' est vide ou n\'est pas un json valide');
        }

        return $data;
    }

    private function checkFile()
    {
        return  is_file($this->getPath());
    }

    private function getPath() {
        return $this->directory .
            $this->fileName;
    }

    private function readJson(): string
    {
        return file_get_contents(
            $this->getPath()
        );
    }
}