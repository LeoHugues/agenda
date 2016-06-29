<?php

namespace PostBundle\Form;


use PostBundle\Entity\Post;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Created by PhpStorm.
 * User: leo
 * Date: 6/29/16
 * Time: 10:38 PM
 */
class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title');
        $builder->add('description', 'textarea');
        $builder->add('concerneDate');
        $builder->add('priority', 'choice', array(
            'choices' => array(
                Post::TEST_PRIORITY         => 'Examen' ,
                Post::HOME_WORK_PRIORITY    => 'Devoir maison' ,
                Post::EXERCICE_PRIORITY     => 'Exercice',
                Post::TODO_PRIORITY         => 'Travail à faire',
                Post::INFORMATION_PRIORITY  => 'Informatif',
            )
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PostBundle\Entity\Post',
        ));
    }

    public function getName()
    {
        return "post_type";
    }
}