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

    public static function getTransitionsForState($state)
    {
        $transitions = [];
        switch ($state) {
            case self::STATE_DRAFT:
                $transitions = [
                    self::TRANSITION_TO_PUBLISH,
                    self::TRANSITION_TO_ABANDONNE,
                    self::TRANSITION_TO_ARCHIVE
                ];
                break;
            case self::STATE_PUBLISHED:
                $transitions = [
                    self::TRANSITION_TO_DRAFT,
                    self::TRANSITION_TO_ARCHIVE,
                    self::TRANSITION_TO_ABANDONNE];
                break;
            case self::STATE_ARCHIVED:
                $transitions = [
                    self::TRANSITION_TO_ABANDONNE,
                    self::TRANSITION_TO_DRAFT
                ];
                break;
            case self::STATE_ABANDONNED:
                $transitions = [
                    self::TRANSITION_TO_DRAFT
                ];
                break;
        }

        return $transitions;
    }
    public static function getNameOfState(string $state)
    {
        $stateName = '';
        switch ($state) {
            case self::STATE_DRAFT:
                $stateName = ' Brouillon';
                break;
            case self::STATE_PUBLISHED:
                $stateName = ' Publié';
                break;
            case self::STATE_ABANDONNED:
                $stateName = ' Abandonné';
                break;
            case self::STATE_ARCHIVED:
                $stateName = ' Archivé';
                break;
        }
        return $stateName;
    }
    public static function getIconOfState(string $state)
    {
        $stateName = '';
        switch ($state) {
            case self::STATE_DRAFT:
                $stateName = '<i class="fab fa-firstdraft text-info"></i>';
                break;
            case self::STATE_PUBLISHED:
                $stateName = '<i class="fab fa-product-hunt text-success"></i>';
                break;
            case self::STATE_ABANDONNED:
                $stateName = '<i class="far fa-trash-alt text-danger"></i>';
                break;
            case self::STATE_ARCHIVED:
                $stateName = '<i class="fas fa-archive text-warning"></i>';
                break;
        }
        return $stateName;
    }
    public static function getTitleOfMail(string $state)
    {
        $stateName = '';
        switch ($state) {
            case self::STATE_PUBLISHED:
                $stateName = ' Un porte document est publié';
                break;
            case self::STATE_DRAFT:
                $stateName = ' Un porte document est passé à l\'état brouillon';
                break;
            case self::STATE_ABANDONNED:
                $stateName = ' Un porte document est abandonné';
                break;
            case self::STATE_ARCHIVED:
                $stateName = ' Un porte document est archivé';
                break;
        }
        return $stateName;
    }
    public static function getShortNameOfState(string $state)
    {
        return self::getNameOfState($state);
    }

    public static function getColorOfState(string $state)
    {
        $stateColor = '';
        switch ($state) {
            case self::STATE_PUBLISHED:
                $stateColor = '#d4edda';
                break;
            case self::STATE_DRAFT:
                $stateColor = '#beebff';
                break;
            case self::STATE_ABANDONNED:
                $stateColor = '#f8d7da';
                break;
            case self::STATE_ARCHIVED:
                $stateColor = '#fff3cd';
                break;
        }

        return $stateColor;
    }

    public static function getModalDataForTransition(string $transition)
    {
        $data=[
            'state'=>'',
            'transition'=>$transition,
            'titre'=>'',
            'btn_label'=>''
        ];

        switch ($transition) {
            case self::TRANSITION_TO_DRAFT:
                $data['state']=self::STATE_DRAFT;
                $data['titre']='Remettre en brouillon';
                $data['btn_label']='Basculer';
                break;
            case self::TRANSITION_TO_PUBLISH:
                $data['state']=self::STATE_PUBLISHED;
                $data['titre']='Publier de porte document';
                $data['btn_label']='Publier';
                break;
            case self::TRANSITION_TO_ABANDONNE:
                $data['state']=self::STATE_ABANDONNED;
                $data['titre']='Abandonner l\'action';
                $data['btn_label']='Abandonner';
                break;
            case self::TRANSITION_TO_ARCHIVE:
                $data['state']=self::STATE_ARCHIVED;
                $data['titre']='Archiver le porte document';
                $data['btn_label']='Archiver';
                break;

        }

        return $data;
    }

}