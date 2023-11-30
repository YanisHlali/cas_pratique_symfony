<?php
// src/Form/BookType.php

namespace App\Form;

use App\Entity\Authors;
use App\Entity\Books;
use App\Entity\Categories;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class BooksType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('title')
        ->add('author', EntityType::class, [
            'label' => 'Auteur:',
            'required' => false,
            'class' => Authors::class,
            'choice_label' => 'name',
            'query_builder' => function (EntityRepository $er): QueryBuilder {
                return $er->createQueryBuilder('a')
                    ->orderBy('a.name', 'ASC');
            },
            'expanded' => false,
            'multiple' => false, // Un seul auteur peut être sélectionné pour un livre
            'by_reference' => true, // Cela peut être omis puisque c'est la valeur par défaut pour les champs non multiples
        ])    
            ->add('category', EntityType::class, [
                'label' => 'Catégorie:',
                'required' => false,
                'class' => Categories::class,
                'choice_label' => 'name',
                'query_builder' => function (EntityRepository $er): QueryBuilder {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.name', 'ASC');
                },
                'expanded' => false,
                'multiple' => true,
            ])
            ->add('isbn', null, [
                'label' => 'ISBN:',
                'required' => false,
            ])
            ->add('date', DateType::class, [
                'label' => 'Date de publication:',
                'required' => false,
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
            ])
            // ... autres champs ...
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Books::class,
        ]);
    }
}
