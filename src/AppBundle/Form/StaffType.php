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
		    ->add('username', 'text', array('label' => 'Имя пользователя', 'attr' => array('placeholder' => 'Логин')))
            ->add('password', 'text', array('label' => 'Пароль', 'attr' => array('placeholder' => 'Пароль')))
            ->add('email', 'email', array('label' => 'E-mail адрес', 'attr' => array('placeholder' => 'Имя_пользователя@Имя_домена')))
            ->add('fullname', 'text', array('label' => 'Полное имя (ФИО)', 'required' => false, 'attr' => array('placeholder' => 'Фамилия Имя Отчество', 'pattern' => '\D+(\s\D+){2}')))
            ->add('gender', 'choice', array('choices' => array(NULL => 'Не указан', 'm' => 'Мужской', 'f' => 'Женский'), 'label' => 'Пол', 'required' => false))
            ->add('birthday', 'date', array('label' => 'Дата рождения', 'format' => 'dd MM yyyy', 'years' => range(date('Y')-5, date('Y')-100), 'placeholder' => array('year' => 'Год', 'month' => 'Месяц', 'day' => 'День'), 'required' => false))
            ->add('district', 'text', array('label' => 'Округ работы', 'required' => false, 'attr' => array('placeholder' => 'Округ работы')))
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
