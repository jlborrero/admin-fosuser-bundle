<?php

namespace Jlbs\AdminFOSUserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre')
            ->add('p_apellido', null, array('label' => 'Primer Apellido', 'requred' => true))
            ->add('s_apellido', null, array('label' => 'Segundo Apellido', 'required' => true))
            ->add('username', null, array('label' => 'Usuario'))
            ->add('email')
            ->add('enabled', null, array('required' => false,'label' => 'Habilitado'))
            ->add('plain_password', 'password', array('label' => 'Contraseña'))
            ->add('nombre', null, array('required' => false))
            ->add('p_apellido', null, array('label' => 'Primer Apellido'))
            ->add('s_apellido', null, array('label' => 'Primer Apellido'))

            ->add(
                'roles',
                'choice',
                array(
                    'required' => true,
                    'multiple' => true,
                    'expanded' => true,
                    'choices' => $this->refactorRoles($options['roles'])
                )
            );
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'roles' => null,
                'validation_groups' => array('Profile'),
                'data_class' => 'Jlbs\AdminFOSUserBundle\Entity\User'
            )
        );
    }

    public function getName()
    {
        return 'core_userbundle_usertype';
    }


    private function refactorRoles($originRoles)
    {
        $roles = array();
        $rolesAdded = array();

        // Add herited roles
        foreach ($originRoles as $roleParent => $rolesHerit) {
            $tmpRoles = array_values($rolesHerit);
            $rolesAdded = array_merge($rolesAdded, $tmpRoles);
            $roles[$roleParent] = array_combine($tmpRoles, $tmpRoles);
        }


        return $roles;

    }

}
