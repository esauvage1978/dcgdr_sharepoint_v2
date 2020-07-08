<?php

namespace App\Form\Comment;

use App\Entity\Backpack;
use App\Entity\Comment;
use App\Entity\User;
use App\Form\AppTypeAbstract;
use App\Repository\BackpackRepository;
use App\Repository\CorbeilleRepository;
use App\Repository\RubricRepository;
use App\Repository\UnderRubricRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentFormType extends AppTypeAbstract
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
         $builder
            ->add('subject', TextType::class, [
                self::LABEL => ' Sujet',
                self::ATTR => ['placeholder' => 'Objet du commentaire : '],
                self::REQUIRED => true
            ])
            ->add('content', TextareaType::class, [
                self::LABEL => ' Message',
                self::REQUIRED => true,
                self::ATTR => [self::ROWS => 8, self::CSS_CLASS => 'textarea'],
            ])
            ->add('usersTo', EntityType::class, [
                self::CSS_CLASS => User::class,
                self::CHOICE_LABEL => 'name',
                self::LABEL=>'A',
                self::MULTIPLE => true,
                self::ATTR => [self::CSS_CLASS => 'select2'],
                self::REQUIRED => false,
                self::QUERY_BUILDER => function (EntityRepository $er) use($options)  {
                    $q1 = $er->createQueryBuilder(UserRepository::ALIAS . '1')
                        ->select(UserRepository::ALIAS . '1.id')
                        ->Join(UserRepository::ALIAS . '1.corbeilles', CorbeilleRepository::ALIAS_UNDERRUBRIC_WRITERS)
                        ->Join(CorbeilleRepository::ALIAS_UNDERRUBRIC_WRITERS . '.underrubricWriters', UnderRubricRepository::ALIAS)
                        ->Join(UnderRubricRepository::ALIAS . '.backpacks', BackpackRepository::ALIAS)
                        ->Where(BackpackRepository::ALIAS . '.id= :idbackpack')
                        ;
                    $q2 = $er->createQueryBuilder(UserRepository::ALIAS . '2')
                        ->select(UserRepository::ALIAS . '2.id')
                        ->Join(UserRepository::ALIAS . '2.corbeilles', CorbeilleRepository::ALIAS_RUBRIC_WRITERS)
                        ->Join(CorbeilleRepository::ALIAS_RUBRIC_WRITERS . '.rubricWriters', RubricRepository::ALIAS)
                        ->Join(RubricRepository::ALIAS . '.underRubrics', UnderRubricRepository::ALIAS .'1')
                        ->Join(UnderRubricRepository::ALIAS . '1.backpacks', BackpackRepository::ALIAS.'1')
                        ->Where(BackpackRepository::ALIAS . '1.id= :idbackpack')
                    ;
                    return $er->createQueryBuilder(UserRepository::ALIAS)
                        ->select(
                            UserRepository::ALIAS
                        )
                        ->andWhere(
                            UserRepository::ALIAS . '.id IN (' . $q1->getDQL() . ')' .
                            'or ' . UserRepository::ALIAS . '.id IN (' . $q2->getDQL() . ')'  )
                        ->setParameter('idbackpack',$options['data']['data'])
                        ->orderBy(UserRepository::ALIAS.'.name', 'ASC');
                },
            ]);
    }


}
