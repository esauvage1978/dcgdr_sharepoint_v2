<?php


namespace App\Helper;



/**
 * @author Emmanuel SAUVAGE <emmanuel.sauvage@live.fr>
 * @version 1.0.0
 */
class SplitFile
{
    private $name;
    private $extension;

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getExtension()
    {
        return $this->extension;
    }

    public function split($file)
    {
        $explode=explode('.',$file);
        $this->extension=$explode[sizeof($explode) - 1];
        $this->name=substr($file,0,strlen($file)-strlen($this->extension)-1);
    }
}