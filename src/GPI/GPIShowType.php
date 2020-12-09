<?php

namespace App\GPI;

final class GPIShowType
{
    const SUCCESS = 'success';
    const WARNING = 'warning';
    const DANGER = 'danger';
    const INFO = 'info';
    const LIGHT = 'light';
    const SECONDARY = 'secondary';

    public static function getDatas(): array
    {
        return [
            'Vert' => self::SUCCESS,
            'Jaune' => self::WARNING,
            'Rouge' => self::DANGER,
            'Bleu' => self::INFO,
            'Gris clair' => self::LIGHT,
            'Gris foncé' => self::SECONDARY,
        ];
    }

    public static function getName($key)
    {
        self::checkData($key);
        return array_search($key,self::getDatas());
    }

    public static function hasData(string $data): bool
    {
        $datas = [
            self::SUCCESS,
            self::WARNING,
            self::DANGER,
            self::INFO,
            self::LIGHT,
            self::SECONDARY,
        ];

        if (in_array($data, $datas)) {
            return true;
        }
        return false;
    }

    public static function checkData($data)
    {
        if(!self::hasData($data)) {
            throw new \InvalidArgumentException('Ce paramètre n\'est pas présent !');
        }
    }
}