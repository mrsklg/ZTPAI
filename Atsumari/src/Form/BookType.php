<?php

namespace App\Form;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\Genre;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => false,
                'attr' => ['placeholder' => 'Title']
            ])
            ->add('num_of_pages', IntegerType::class, [
                'label' => false,
                'attr' => ['placeholder' => 'Number of pages']
            ])
            ->add('coverFile', FileType::class, [
                'label' => false,
                'mapped' => false,
                'required' => false,
            ])
//            ->add('authors', CollectionType::class, [
//                'entry_type' => AuthorType::class,
//                'allow_add' => true,
//                'allow_delete' => true,
//                'by_reference' => false,
//                'label' => 'Authors'
//            ])
            ->add('authors', CollectionType::class, [
                'entry_type' => AuthorType::class,
                'entry_options' => [
                    'label' => false,
                ],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
//                'label' => 'Authors',
                'attr' => ['class' => 'authors-collection'],
            ])
            ->add('genres', CollectionType::class, [
                'entry_type' => GenreType::class,
                'entry_options' => [
                    'label' => false,
                ],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
//                'label' => 'Genres',
                'attr' => ['class' => 'genres-collection'],
            ]);
//            ->add('genres', CollectionType::class, [
//                'entry_type' => GenreType::class,
//                'allow_add' => true,
//                'allow_delete' => true,
//                'by_reference' => false,
//                'label' => 'Genres'
//            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
            'csrf_protection' => false,
        ]);
    }
}
