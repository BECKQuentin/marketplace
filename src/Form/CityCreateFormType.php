<?php

namespace App\Form;

use App\Entity\City;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\Length;

class CityCreateFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'constraints' => [
                    new Length([
                        'min' => 3,
                        'minMessage' => 'Merci d\'entrer au moins 3 caractères',
                        'max' => 255,
                    ]),
                ],
            ])
            ->add('lat')
            ->add('lng')
            ->add('postCode', TextType::class, [
                'constraints' => [
                    new Length([
                        'min' => 5,
                        'minMessage' => 'Merci d\'entrer au moins 5 caractères',
                        'max' => 5,
                    ]),
                ],
            ])
            ->add('Envoyer', SubmitType::class)
        ;
    }
}
