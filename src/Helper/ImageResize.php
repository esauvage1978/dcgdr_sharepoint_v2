<?php


namespace App\Helper;


use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Mime\MimeTypes;

class ImageResize
{
    /**
     * @var ParameterBagInterface
     */
    private $params;

    private $x;
    private $y;
    private $imagePath;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    public function resize(string $imagePath)
    {
        if (!is_file($imagePath)) {
            return;
        }

        $this->imagePath = $imagePath;
        $this->x = $this->params->get('image.resize.x');
        $this->y = $this->params->get('image.resize.y');


        switch ($this->guessExtension()) {
            case "jpg":
            case "jpeg":
                $this->resizeJpeg();
                break;
            case "png":
                $this->resizePng();
                break;
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
        } catch  (\Exception $e) {
            dump($e->getMessage());
        }
    }
}