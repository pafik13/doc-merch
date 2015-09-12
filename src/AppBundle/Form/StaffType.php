<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StaffType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
		    ->add('username', 'text', array('label' => 'Имя пользователя'))
            ->add('password', 'text', array('label' => 'Пароль'))
            ->add('email', 'email', array('label' => 'E-mail адрес'))
            ->add('fullname', 'text', array('label' => 'Полное имя (ФИО)', 'required' => false))
            ->add('gender', 'choice', array('choices' => array('Мужской', 'Женский', 'Не указан'), 'label' => 'Пол'))
            ->add('birthday', 'date', array('label' => 'Дата рождения', 'years' => range(date('Y')-5, date('Y')-100), 'required' => false))
            ->add('job', 'text', array('label' => 'Место работы', 'required' => false))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\User'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_user';
    }
}
