<?php


namespace App\Workflow;


class WorkflowData
{
    const STATE_DRAFT = 'draft';
    const STATE_ABANDONNED = 'abandonned';
    const STATE_PUBLISHED = 'published';
    const STATE_ARCHIVED = 'archived';


    const TRANSITION_TO_PUBLISH = 'toPublish';
    const TRANSITION_TO_ABANDONNE = 'toAbandonne';
    const TRANSITION_TO_ARCHIVE = 'toArchive';
    const TRANSITION_TO_DRAFT = 'toTheDraft';

    private const NAME = 'name';
    private const ICON = 'icon';
    private const TITLE_MAIL = 'title_mail';
    private const BGCOLOR = 'bgcolor';
    private const FORECOLOR = 'forecolor';
    private const TRANSITIONS = 'transitions';

    private static function getStates(): array
    {
        return [
            self::STATE_DRAFT =>
            [
                self::NAME => ' Brouillon',
                self::ICON => 'fab fa-firstdraft',
                self::TITLE_MAIL => ' Un porte-document est passé à l\'état brouillon',
                self::BGCOLOR => '#440155',
                self::FORECOLOR => '#ffffff',
                self::TRANSITIONS => [
                    self::TRANSITION_TO_PUBLISH,
                    self::TRANSITION_TO_ABANDONNE,
                    self::TRANSITION_TO_ARCHIVE
                ]
            ],
            self::STATE_PUBLISHED =>
            [
                self::NAME => ' Publié',
                self::ICON => 'fab fa-product-hunt',
                self::TITLE_MAIL => ' Un porte-document est publié',
                self::BGCOLOR => '#ff6584',
                self::FORECOLOR => '#ffffff',
                self::TRANSITIONS => [
                    self::TRANSITION_TO_DRAFT,
                    self::TRANSITION_TO_ARCHIVE,
                    self::TRANSITION_TO_ABANDONNE
                ]
            ],
            self::STATE_ABANDONNED =>
            [
                self::NAME => ' Abandonné',
                self::ICON => 'far fa-trash-alt',
                self::TITLE_MAIL => ' Un porte-document est abandonné',
                self::BGCOLOR => '#AA0C0C',
                self::FORECOLOR => '#ffffff',
                self::TRANSITIONS => [
                    self::TRANSITION_TO_DRAFT
                ]
            ],
            self::STATE_ARCHIVED =>
            [
                self::NAME => ' Archivé',
                self::ICON => 'fas fa-archive',
                self::TITLE_MAIL => ' Un porte-document est archivé',
                self::BGCOLOR => '#F6B50F',
                self::FORECOLOR => '#F64D14',
                self::TRANSITIONS => [
                    self::TRANSITION_TO_ABANDONNE,
                    self::TRANSITION_TO_DRAFT
                ]
            ],
        ];
    }

    public function hasData(string $data): bool
    {
        return self::hasState($data) && self::hasTransition($data);
    }

    public static function hasState(string $data): bool
    {
        $datas = [
            self::STATE_DRAFT,
            self::STATE_ABANDONNED,
            self::STATE_PUBLISHED,
            self::STATE_ARCHIVED,
        ];

        if (in_array($data, $datas)) {
            return true;
        }
        return false;
    }

    public static function hasTransition(string $data): bool
    {
        $datas = [
            self::TRANSITION_TO_PUBLISH,
            self::TRANSITION_TO_ABANDONNE,
            self::TRANSITION_TO_ARCHIVE,
            self::TRANSITION_TO_DRAFT,
        ];

        if (in_array($data, $datas)) {
            return true;
        }
        return false;
    }

    private static function  getStatesValue($state, $data)
    {
        if (!self::hasState($state)) {
            throw new \InvalidArgumentException('cet état n\'existe pas');
        }
        return self::getStates()[$state][$data];
    }
    public static function getTransitionsForState($state)
    {
        return self::getStatesValue($state, self::TRANSITIONS);
    }

    public static function getNameOfState(string $state)
    {
        return self::getStatesValue($state, self::NAME);
    }

    public static function getIconOfState(string $state)
    {
        return self::getStatesValue($state, self::ICON);
    }

    public static function getTitleOfMail(string $state)
    {
        return self::getStatesValue($state, self::TITLE_MAIL);
    }

    public static function getShortNameOfState(string $state)
    {
        return self::getStatesValue($state, self::NAME);
    }


    public static function getBGColorOfState(string $state)
    {
        return self::getStatesValue($state, self::BGCOLOR);
    }
    public static function getForeColorOfState(string $state)
    {
        return self::getStatesValue($state, self::FORECOLOR);
    }

    public static function getModalDataForTransition(string $transition)
    {
        if (!self::hasTransition($transition)) {
            throw new \InvalidArgumentException('Cette transition n\'existe pas');
        }
        $data = [
            'state' => '',
            'transition' => $transition,
            'titre' => '',
            'btn_label' => ''
        ];

        switch ($transition) {
            case self::TRANSITION_TO_DRAFT:
                $data['state'] = self::STATE_DRAFT;
                $data['titre'] = 'Remettre en brouillon';
                $data['btn_label'] = 'Basculer';
                break;
            case self::TRANSITION_TO_PUBLISH:
                $data['state'] = self::STATE_PUBLISHED;
                $data['titre'] = 'Publier de porte document';
                $data['btn_label'] = 'Publier';
                break;
            case self::TRANSITION_TO_ABANDONNE:
                $data['state'] = self::STATE_ABANDONNED;
                $data['titre'] = 'Abandonner l\'action';
                $data['btn_label'] = 'Abandonner';
                break;
            case self::TRANSITION_TO_ARCHIVE:
                $data['state'] = self::STATE_ARCHIVED;
                $data['titre'] = 'Archiver le porte document';
                $data['btn_label'] = 'Archiver';
                break;
        }

        return $data;
    }
}
