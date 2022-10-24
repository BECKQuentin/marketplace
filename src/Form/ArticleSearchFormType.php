<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\City;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Length;

class ArticleSearchFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, ['required' => false, 
                'constraints' => [
                    new Length([
                        'min' => 3,
                        'minMessage' => 'Merci d\'entrer au moins 3 caractères',
                        'max' => 255,
                    ]),
                ],
            ])
            ->add('description_too', CheckboxType::class, [
                'label'    => 'Chercher aussi dans la description',
                'required' => false,
            ])
            ->add('price_min', NumberType::class, ['label' => 'Prix min', 'required' => false])
            ->add('price_max', NumberType::class, ['label' => 'Prix max', 'required' => false])
            ->add('category', EntityType::class, ['class' => Category::class, 'choice_label' => 'name', 'required' => false, 'empty_data' => 'Toutes',])
            ->add('city', EntityType::class, ['class' => City::class, 'choice_label' => 'name', 'required' => false, 'empty_data' => 'Toute la France',])
            ->add('Envoyer', SubmitType::class)
        ;
    }
}
