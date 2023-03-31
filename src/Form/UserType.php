<?php

namespace App\Form;

use App\Entity\User;
use DateTimeImmutable;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('firstname', null, [
                'label' => 'Prenom'
            ])
            ->add('username', null, [
                'label' => 'Pseudo'
            ])
            ->add('avatar', FileType::class, array('data_class' => null),[
                'mapped' => 'false',
                'required' => 'false',
                'constraints' => [
                    new File([
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/jpg',
                            'image/png',
                            'image/jfif',
                        ]
                    ])
                ]
            ])
                
            ->add('dateOfBirthAt', BirthdayType::class , array(
                'widget' => 'choice',
                'label' => 'date de naissance',
                'input' => 'datetime_immutable',
                // 'years' => range(date('Y'), date('Y')-100),
                // 'months' => range('m', 12),
                // 'days' => range('d', 31),
              ))
            ->add('biography', null, [
                'label' => 'biographie'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
