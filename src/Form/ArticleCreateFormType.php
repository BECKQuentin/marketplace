<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\City;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Length;

class ArticleCreateFormType extends AbstractType
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
            ->add('description', TextType::class, [
                'constraints' => [
                    new Length([
                        'min' => 3,
                        'minMessage' => 'Merci d\'entrer au moins 3 caractères',
                        'max' => 255,
                    ]),
                ],
            ])
            ->add('price', NumberType::class)
            ->add('city', EntityType::class, ['class' => City::class, 'choice_label' => 'name',])
            ->add('category', EntityType::class, ['class' => Category::class, 'choice_label' => 'name',])
            ->add('Envoyer', SubmitType::class)
        ;
    }
}
