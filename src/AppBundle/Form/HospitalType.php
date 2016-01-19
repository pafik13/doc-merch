<?php

namespace AppBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HospitalType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('shortName', TextType::class, array('label' => 'Краткое наименование'))
            ->add('fullName', TextType::class, array('label' => 'Полное наименование', 'attr' => array('pattern' => '[А-Я][а-я]+')))
            ->add('territory', EntityType::class, array('class'=>'AppBundle:Territory','choice_label'=>'name','label' => 'Округ','placeholder'=>'Не указан', 'required' => false))
            ->add('address', TextType::class, array('label'=>'Адрес','read_only'=>'read_only'))
            ->add('ka_region',TextType::class, array('label'=>'Регион'))
            ->add('ka_district',TextType::class, array('label'=>'Район'))
            ->add('ka_city',TextType::class, array('label'=>'Город'))
            ->add('ka_street',TextType::class, array('label'=>'Улица'))
            ->add('ka_building',TextType::class, array('label'=>'Номер дома'))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Hospital'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_hospital';
    }
}
