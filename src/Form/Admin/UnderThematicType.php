<?php

namespace App\Form\Admin;

use App\Entity\UnderThematic;
use App\Form\AppTypeAbstract;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UnderThematicType extends AppTypeAbstract
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->buildFormName($builder);
        $this->buildFormIsEnable($builder);
        $this->buildFormContent($builder);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UnderThematic::class,
        ]);
    }
}
