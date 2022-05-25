<?php

namespace App\Form;

use App\Entity\Player;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class PlayerFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => array(
                    'class' => 'mx-4',
                ),
                'label' => 'Nombre',
                'required' => false
            ])
            ->add('last_name', TextType::class, [
                'attr' => array(
                    'class' => 'mx-4',
                ),
                'label' => 'Apellidos: ',
                'required' => false
            ])
            ->add('age', TextType::class, [
                'attr' => array(
                    'class' => 'mx-4',
                ),
                'label' => 'Edad: ',
                'required' => false
            ])
            ->add('email', TextType::class, [
                'attr' => array(
                    'class' => 'mx-5',
                ),
                'label' => 'Correo Electrónico: ',
                'required' => false
            ])
            ->add('balance', NumberType::class, [
                'attr' => array(
                    'class' => 'mx-5'
                ),
                'label' => 'Balance Económico: ',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Player::class,
        ]);
    }
}
