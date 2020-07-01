<?php

namespace App\Form\Admin;


use App\Entity\Picture;
use App\Form\AppTypeAbstract;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PictureType extends AppTypeAbstract
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->buildFormName($builder);
        $this->buildFormIsEnable($builder);
        $this->buildFormContent($builder);
        $builder
            ->add('file', FileType::class,
                [
                    self::LABEL			=> 'Choisir le fichier',
                    self::REQUIRED=>false
                ])
            ->add('updateAt', DateTimeType::class,
                [
                    self::LABEL			=> 'Choisir le fichier',
                    self::REQUIRED=>false
                ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Picture::class,
        ]);
    }
}
