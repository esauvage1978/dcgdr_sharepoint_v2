<?php

namespace App\Form\Admin;

use App\Entity\User;
use App\Form\AppTypeAbstract;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AppTypeAbstract
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder = $this->buildFormName($builder);
        $builder = $this->buildFormIsEnable($builder);
        $builder = $this->buildFormContent($builder);

        $roles = [
            'Utilisateur' => 'ROLE_USER',
            'Gestionnaire' => 'ROLE_GESTIONNAIRE',
            'Administrateur' => 'ROLE_ADMIN',
        ];

        $builder
            ->add('email', EmailType::class)
            ->add('phone',TelType::class,
                [
                    self::LABEL => 'Téléphone',
                    self::REQUIRED => false,
                ])
            ->add('roles', ChoiceType::class, [
                'choices' => $roles,
                'multiple' => true,
                'expanded' => true,
                'mapped' => true,
                self::LABEL => 'form.roles',
            ])
            ->add('emailValidated', CheckboxType::class,
                [
                    self::LABEL => ' ',
                    self::REQUIRED => false,
                ])
            ->add('subscription', CheckboxType::class,
                [
                    self::LABEL => ' Permet d\'abonner l\'utilisateur aux rubriques et de recevoir hebdomadairement toutes les actualités',
                    self::REQUIRED => false,
                ])

            ->add('loginAt')
            ->add('createdAt')
            ->add('modifiedAt')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
