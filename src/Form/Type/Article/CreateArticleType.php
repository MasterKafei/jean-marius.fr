<?php

namespace App\Form\Type\Article;

use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Tbmatuka\EditorjsBundle\Form\EditorjsType;

class CreateArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'title',
                TextType::class,
                []
            )
            ->add(
                'content',
                EditorjsType::class,
                []
            )
            ->add(
                'summary',
                TextareaType::class,
                []
            )
            ->add(
                'preview',
                FileType::class,
                ['mapped' => false]
            )
            ->add(
                'slug',
                TextType::class,
                []
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
