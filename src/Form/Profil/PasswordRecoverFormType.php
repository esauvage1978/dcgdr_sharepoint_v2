<?php


namespace App\Form\Profil;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class PasswordRecoverFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('plainPassword', PasswordType::class, [
                'label'=>"Nouveau mot de passe",
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci de saisir le mot de passe',
                    ]),
                    new Length([
                        'min' => 8,
                        'max' => 255,
                    ]), ], ])
            ->add('plainPasswordConfirmation', PasswordType::class,[
                'label'=>"Confirmation",
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
        ]);
    }
}
