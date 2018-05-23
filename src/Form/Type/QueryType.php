<?php declare(strict_types=1);

namespace AlertApi\Form\Type;

use AlertApi\Form\Model\Query;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QueryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->setMethod('GET');
        $builder->add('type', TextType::class, [
            'required' => false
        ]);
        $builder->add('latitude', TextType::class, [
            'required' => true
        ]);;
        $builder->add('longitude', TextType::class, [
            'required' => true
        ]);
        $builder->add('distance', NumberType::class, [
            'required' => false
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Query::class,
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}
