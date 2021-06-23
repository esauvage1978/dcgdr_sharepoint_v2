<?php

namespace App\Helper;

use Symfony\Component\DependencyInjection\Exception\ParameterNotFoundException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * @author Emmanuel SAUVAGE <emmanuel.sauvage@live.fr>
 * @version 1.0.0
 */
class ParamsInServices
{
    CONST APPLICATION_NAME='application.name';
    CONST DIRECTORY_AVATAR= 'directory_avatar';
    CONST DIRECTORY_FIXTURES_DOC= 'directory_fixtures_doc';
    CONST DIRECTORY_FIXTURES_JSON='directory_fixtures_json';
    CONST DIRECTORY_FIXTURES_PICTURE='directory_fixtures_picture';
    CONST DIRECTORY_PICTURE='directory_picture';
    CONST DIRECTORY_UPLOAD_BACKPACK_DOC='directory_upload_backpack_doc';
    CONST IMAGE_RESIZE_X='image.resize.x';
    CONST IMAGE_RESIZE_Y='image.resize.y';
    CONST MAILER_NAME='mailer.name';
    CONST MAILER_MAIL='mailer.mail';
    CONST MAILER_PREFIXE='mailer.prefixe';
    CONST MAILER_SMTP_PASSWORD='mailer.smtp.password';
    CONST MAILER_SMTP_USERNAME='mailer.smtp.username';
    CONST MAILER_SMTP_HOST='mailer.smtp.host';
    CONST MAILER_SMTP_PORT='mailer.smtp.port';
    CONST NEWS_TIME='news_time';
    public const ES_TREE_UNDEVELOPPED_NBR = 'es.tree.undevelopped.nbr';


    /**
     * @var ParameterBagInterface
     */
    private $params;

    /**
     * @var array $datas
     */
    private $datas=[];

    /**
     * ParamsInServices constructor.
     * @param ParameterBagInterface $params
     */
    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
        $this->datas=[
            self::APPLICATION_NAME,
            self::DIRECTORY_AVATAR,
            self::DIRECTORY_FIXTURES_DOC,
            self::DIRECTORY_FIXTURES_JSON,
            self::DIRECTORY_FIXTURES_PICTURE,
            self::DIRECTORY_PICTURE,
            self::DIRECTORY_UPLOAD_BACKPACK_DOC,
            self::IMAGE_RESIZE_X,
            self::IMAGE_RESIZE_Y,
            self::MAILER_NAME,
            self::MAILER_MAIL,
            self::MAILER_PREFIXE,
            self::MAILER_SMTP_PASSWORD,
            self::MAILER_SMTP_USERNAME,
            self::MAILER_SMTP_HOST,
            self::MAILER_SMTP_PORT,
            self::NEWS_TIME,
            self::ES_TREE_UNDEVELOPPED_NBR
        ];
    }

    /**
     * Récupère la valeur paramètre présente dans le fichiers config/services.yaml.
     * Utiliser les constantes présentes dans cette classe
     *
     * @param string $param_name
     * @return string
     * @throws ParameterNotFoundException if the parameter is not defined
     */
    public function get(string $param_name) :string
    {
        if(!in_array($param_name,$this->datas)){
            throw new \InvalidArgumentException('Ce paramètre est incconnu : '. $param_name);
        }
        return $this->params->get($param_name);
    }

}