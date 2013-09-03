<?php

namespace Jlbs\AdminFOSUserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use FOS\UserBundle\Model\UserManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadUserData extends AbstractFixture implements ContainerAwareInterface
{
    private $container;

    function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {

        $entity = $this->getUserManager()->createUser();
        $entity->setNombre('Administrador')
            ->setPApellido('Apellido1')
            ->setSApellido('Apellido2')
            ->setUsername('admin')
            ->setPlainPassword('admin')
            ->setRoles(array('ROLE_USER', 'ROLE_ADMIN'))
            ->setEmail('admin@email.com')
            ->setEnabled(true);
        $this->getUserManager()->updatePassword($entity);
        $manager->persist($entity);
        $manager->flush();
    }

    /**
     * @return UserManager
     */

    private function getUserManager()
    {
        return $this->container->get('fos_user.user_manager');

    }

}