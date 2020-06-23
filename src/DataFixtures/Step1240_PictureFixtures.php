<?php

namespace App\DataFixtures;

use App\Entity\Picture;
use App\Entity\Rubric;
use App\Entity\Thematic;
use App\Entity\UnderRubric;
use App\Entity\UnderThematic;
use App\Helper\FixturesImportData;
use App\Manager\PictureManager;
use App\Repository\PictureRepository;
use App\Repository\RubricRepository;
use App\Repository\ThematicRepository;
use App\Repository\UnderThematicRepository;
use App\Validator\RubricValidator;
use App\Validator\UnderRubricValidator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;

class Step1240_PictureFixtures extends Fixture implements FixtureGroupInterface
{

    /**
     * @var Picture[]
     */
    private $pictures;

    /**
     * @var PictureManager
     */
    private $pictureManager;

    public function __construct(
        PictureRepository $pictureRepository,
        PictureManager $pictureManager
    ) {
        $this->pictures = $pictureRepository ->findAll();
        $this->pictureManager = $pictureManager;
    }

    public function load(ObjectManager $manager)
    {

        foreach ($this->pictures as $picture) {

            if($picture->getRubrics()->count()==0 && $picture->getUnderRubrics()->count()==0 ) {
                $this->pictureManager->remove($picture);
            }

        }

    }



    public static function getGroups(): array
    {
        return ['step1240'];
    }


}
