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
    CONST DIRECTIRY_IMPORT='directory_import';
    CONST DIRECTORY_AVATAR= 'directory_avatar';
    CONST DIRECTORY_PICTURE='directory_picture';
    CONST DIRECTORY_DATA_PICTURE='directory_data_picture';
    CONST DIRECTORY_FILE_BACKPACK='directory_file_backpack';
    CONST DIRECTORY_DATA_DOC= 'directory_data_doc';

    CONST APPLICATION_NAME='application.name';
    CONST MAILER_MAIL='mailer.mail';
    CONST MAILER_NAME='mailer.name';
    CONST MAILER_PREFIXE='mailer.prefixe';
    CONST MAILER_SMTP_HOST='mailer.smtp.host';
    CONST MAILER_SMTP_PORT='mailer.smtp.port';
    CONST MAILER_SMTP_USERNAME='mailer.smtp.username';
    CONST MAILER_SMTP_PASSWORD='mailer.smtp.password';

    CONST TREE_UNDEVELOPPED_FOR_NBR= 'tree_undevelopped_for_nbr';

    CONST IMAGE_RESIZE_X='image.resize.x';
    CONST IMAGE_RESIZE_Y='image.resize.y';

    /**
     * @var ParameterBagInterface
     */
    private $params;

    /**
     * ParamsInServices constructor.
     * @param ParameterBagInterface $params
     */
    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
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
        return $this->params->get($param_name);
    }

}