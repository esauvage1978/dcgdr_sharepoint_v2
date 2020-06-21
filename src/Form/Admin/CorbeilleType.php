<?php

namespace App\Form\Admin;

use App\Entity\Corbeille;
use App\Form\AppTypeAbstract;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CorbeilleType extends AppTypeAbstract
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->buildFormGenerique($builder);
        $this->buildFormOrganisme($builder);
    }

    public function buildFormGenerique(FormBuilderInterface $builder): FormBuilderInterface
    {
        $this->buildFormName($builder);
        $this->buildFormIsEnable($builder);
        $this->buildFormContent($builder);
        $this->buildFormUsers($builder);
        $builder
            ->add('isShowRead', CheckboxType::class, [
                self::LABEL => ' ',
                self::REQUIRED => false,
            ])
            ->add('isShowWrite', CheckboxType::class, [
                self::LABEL => ' ',
                self::REQUIRED => false,
            ])
            ;

        return $builder;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Corbeille::class,
        ]);
    }
}
