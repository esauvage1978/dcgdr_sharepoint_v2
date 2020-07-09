<?php


namespace App\Twig;


use App\GPI\GPIPage;
use App\GPI\GPIShowType;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class GPIExtension extends AbstractExtension
{
    public function __construct()
    {
    }

    public function getFilters()
    {
        return [
            new TwigFilter('getNameOfPage', [$this, 'getNameOfPage']),
            new TwigFilter('getNameOfType', [$this, 'getNameOfType']),
        ];
    }


    public function getNameOfPage(string $page)
    {
        return GPIPage::getName($page);
    }

    public function getNameOfType(string $page)
    {
        return GPIShowType::getName($page);
    }
}