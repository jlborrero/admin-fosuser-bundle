<?php
/**
 * Created by JetBrains PhpStorm.
 * User: joe <jlborrero@gmail.com>
 * Date: 31/05/13
 * Time: 11:24
 * To change this template use File | Settings | File Templates.
 */

namespace Jlbs\AdminFOSUserBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;

class RegistrationFormType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nombre')
            ->add('p_apellido')
            ->add('s_apellido');
        parent::buildForm($builder, $options);
    }

    public function getName()
    {
        return 'core_user_registration';
    }
}