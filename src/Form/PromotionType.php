<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\Promotion;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PromotionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('product', EntityType::class, [
                // looks for choices from this entity
                'class' => Product::class,
                'choice_label' => 'name'
            ])
            ->add('quantity', IntegerType::class, [
                'required' => true,
            ])
            ->add('price', IntegerType::class, [
                'required' => true
            ])
            ->add('save', SubmitType::class, ['label' => 'Create'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Promotion::class,
        ]);
    }
}
