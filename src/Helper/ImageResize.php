<?php


namespace App\Helper;


use Symfony\Component\Mime\MimeTypes;

/**
 * @author Emmanuel SAUVAGE <emmanuel.sauvage@live.fr>
 * @version 1.0.0
 */
class ImageResize
{
    /**
     * @var ParamsInServices
     */
    private $paramsInServices;

    /**
     * @var int
     */
    private $x;

    /**
     * @var int
     */
    private $y;

    /**
     * @var string
     */
    private $imagePath;

    public function __construct(ParamsInServices $paramsInServices)
    {
        $this->paramsInServices = $paramsInServices;
    }

    /**
     * Redimmentionne et remplace une image par rapport à X / Y (services.yaml)
     * Ne fonctionne que pour les images png et jpg
     *
     * @param string $imagePath
     */
    public function resize(string $imagePath)
    {
        if (!is_file($imagePath)) {
            return;
        }

        $this->imagePath = $imagePath;

        $this->x = $this->paramsInServices->get($this->paramsInServices::IMAGE_RESIZE_X);
        $this->y = $this->paramsInServices->get($this->paramsInServices::IMAGE_RESIZE_Y);


        switch ($this->guessExtension()) {
            case "jpg":
            case "jpeg":
                $this->resizeJpeg();
                break;
            case "png":
                $this->resizePng();
                break;
            default:
                throw new \InvalidArgumentException("Le format de l'\image est incorrect (jpg/jpeg/png");
        }

    }

    private function guessExtension()
    {
        return MimeTypes::getDefault()->getExtensions($this->getMimeType())[0] ?? null;
    }

    private function getMimeType()
    {
        return MimeTypes::getDefault()->guessMimeType($this->imagePath);
    }

    private function resizeJpeg()
    {
        Header("Content-type: image/jpeg");
        $img_new = imagecreatefromjpeg($this->imagePath);
        imagejpeg($this->resizeFormat($img_new), $this->imagePath);
    }

    private function resizePng()
    {
        Header("Content-type: image/png");
        $img_new = imagecreatefrompng($this->imagePath);
        imagepng($this->resizeFormat($img_new), $this->imagePath);
    }

    private function resizeFormat($img_new)
    {
        try {
            $size = getimagesize($this->imagePath);
            $img_mini = imagecreatetruecolor($this->x, $this->y);
            imagecopyresampled($img_mini, $img_new, 0, 0, 0, 0, $this->x, $this->y, $size[0], $size[1]);
            return $img_mini;
        } catch (\Exception $e) {
            dump('Une erreur s\'est produite lors du redimentionnement de l\'image : '.$e->getMessage());
        }
    }
}