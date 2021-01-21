<?php

namespace App\Form\Mailer;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityRepository;
use App\Repository\RubricRepository;
use App\Repository\BackpackRepository;
use App\Repository\MProcessRepository;
use App\Repository\CorbeilleRepository;
use App\Repository\UnderRubricRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class MailerFormBackpackType extends MailerFormType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->buildFormSubjectContent($builder);

        $builder

            ->add('piloter', EntityType::class, [
                self::CSS_CLASS => User::class,
                self::CHOICE_LABEL => 'name',
                self::LABEL => 'Pilote de la rubrique',
                self::MULTIPLE => true,
                self::ATTR => [self::CSS_CLASS => 'select2'],
                self::REQUIRED => false,
                self::QUERY_BUILDER => function (EntityRepository $er) use ($options) {
                    return $er->createQueryBuilder(UserRepository::ALIAS)
                        ->select(
                            UserRepository::ALIAS,
                            CorbeilleRepository::ALIAS,
                            RubricRepository::ALIAS,
                            UnderRubricRepository::ALIAS,
                            BackpackRepository::ALIAS
                        )
                        ->leftJoin(UserRepository::ALIAS . '.corbeilles', CorbeilleRepository::ALIAS)
                        ->leftJoin(CorbeilleRepository::ALIAS . '.rubricWriters', RubricRepository::ALIAS)
                        ->leftJoin(RubricRepository::ALIAS . '.underRubrics', UnderRubricRepository::ALIAS)
                        ->leftJoin(UnderRubricRepository::ALIAS . '.backpacks', BackpackRepository::ALIAS)
                        ->where(BackpackRepository::ALIAS . '.id = :actionid')
                        ->setParameter('actionid', $options['data']['data'])
                        ->orderBy(UserRepository::ALIAS . '.name', 'ASC');
                },
            ])
            ->add('piloteur', EntityType::class, [
                self::CSS_CLASS => User::class,
                self::CHOICE_LABEL => 'name',
                self::LABEL => 'Pilote de la sous rubrique',
                self::MULTIPLE => true,
                self::ATTR => [self::CSS_CLASS => 'select2'],
                self::REQUIRED => false,
                self::QUERY_BUILDER => function (EntityRepository $er) use ($options) {
                    return $er->createQueryBuilder(UserRepository::ALIAS)
                        ->select(
                            UserRepository::ALIAS,
                            CorbeilleRepository::ALIAS,
                            UnderRubricRepository::ALIAS,
                            BackpackRepository::ALIAS
                        )
                        ->leftJoin(UserRepository::ALIAS . '.corbeilles', CorbeilleRepository::ALIAS)
                        ->leftJoin(CorbeilleRepository::ALIAS . '.underrubricWriters', UnderRubricRepository::ALIAS)
                        ->leftJoin(UnderRubricRepository::ALIAS . '.backpacks', BackpackRepository::ALIAS)
                        ->where(BackpackRepository::ALIAS . '.id = :actionid')
                        ->setParameter('actionid', $options['data']['data'])
                        ->orderBy(UserRepository::ALIAS . '.name', 'ASC');
                },
            ]);
    }
}
