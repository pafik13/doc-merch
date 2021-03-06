<?php

namespace AppBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PresenterType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', 'email', array('label' => 'Электронная почта', 'attr' => array('placeholder' => 'example@example.com')))
            ->add('surname', 'text', array('label' => 'Фамилия', 'attr' => array('pattern' => '[А-Я][а-я]+')))
			->add('name', 'text', array('label' => 'Имя', 'attr' => array('pattern' => '[А-Я][а-я]+')))
			->add('patronymic', 'text', array('label' => 'Отчество', 'attr' => array('pattern' => '[А-Я][а-я]+')))
            ->add('gender', 'choice', array('choices' => array(NULL => 'Не указан', 'm' => 'Мужской', 'f' => 'Женский'), 'label' => 'Пол', 'required' => false))
            ->add('birthday', 'date', array('label' => 'Дата рождения', 'format' => 'dd MM yyyy', 'years' => range(date('Y')-5, date('Y')-100), 'placeholder' => array('year' => 'Год', 'month' => 'Месяц', 'day' => 'День'), 'required' => false))
            ->add('territory', EntityType::class, array('class'=>'AppBundle:Territory','choice_label'=>'name','label' => 'Округ работы','placeholder'=>'Не указан', 'required' => false))
            ->add('isActive', CheckboxType::class, array('label'=>'Активный', 'required'=>false) )
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Presenter'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_presenter';
    }
}
