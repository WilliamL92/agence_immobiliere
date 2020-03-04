<?php

namespace App\Form;

use App\Entity\BiensImmobilier;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Validator\Constraints\File;

class BienImmoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', TextType::class)
            ->add('surface', IntegerType::class)
            ->add('prix', IntegerType::class)
            ->add('localisation', TextType::class)
            ->add('images', FileType::class, ['multiple' => 'true',
            'mapped' => 'false',
            'required' => 'true',])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => BiensImmobilier::class,
        ]);
    }
}
