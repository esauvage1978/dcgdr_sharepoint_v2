<?php

namespace App\Helper;

/**
 * @author Emmanuel SAUVAGE <emmanuel.sauvage@live.fr>
 * @version 1.0.0
 */
class FixturesImportData
{
    /**
     * @var ConvertFileJsonToArray
     */
    private $convertFileJsonToArray;

    public function __construct(ParamsInServices $paramsInServices)
    {
        $this->convertFileJsonToArray=new ConvertFileJsonToArray();

        $this->convertFileJsonToArray->setDirectory($paramsInServices->get(ParamsInServices::DIRECTIRY_IMPORT));
    }

    public function importToArray(string $fileName): array
    {
        $this->convertFileJsonToArray->setFileSource($fileName);
        return $this->convertFileJsonToArray->toArray();
    }

}