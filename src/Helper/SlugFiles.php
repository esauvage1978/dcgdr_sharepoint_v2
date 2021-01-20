<?php

namespace App\Helper;

use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @author Emmanuel SAUVAGE <emmanuel.sauvage@live.fr>
 * @version 1.0.0
 */
class SlugFiles
{

    public function toSlug(string $cheminSource)
    {
        $dir = opendir($cheminSource);

        while ($file = readdir($dir)) {

            if (is_file($cheminSource . DIRECTORY_SEPARATOR . $file)) {
                $sf = new SplitNameOfFile($file);

                $slugified = Slugger::slugify($sf->getName());
                if ($file != $slugified . '.' . $sf->getExtension()) {
                    rename(
                        $cheminSource . DIRECTORY_SEPARATOR . $file,
                        $cheminSource . DIRECTORY_SEPARATOR . $slugified . '.' . $sf->getExtension()
                    );
                }
            }
        }
    }
}
