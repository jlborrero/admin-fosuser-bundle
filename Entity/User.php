<?php
namespace Jlbs\AdminFOSUserBundle\Entity;

use FOS\UserBundle\Model\User as AbstractUser,
    Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 */
class User extends AbstractUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string nombre
     *
     * @ORM\Column(name="nombre", type="string", length=100)
     */
    protected $nombre;

    /**
     * @var string $last_name
     *
     * @ORM\Column(name="p_apellido", type="string", length=100)
     */
    protected $p_apellido;

    /**
     * @var string $last_name
     *
     * @ORM\Column(name="s_apellido", type="string", length=100)
     */
    protected $s_apellido;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return User
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set p_apellido
     *
     * @param string $pApellido
     * @return User
     */
    public function setPApellido($pApellido)
    {
        $this->p_apellido = $pApellido;

        return $this;
    }

    /**
     * Get p_apellido
     *
     * @return string
     */
    public function getPApellido()
    {
        return $this->p_apellido;
    }

    /**
     * Set s_apellido
     *
     * @param string $sApellido
     * @return User
     */
    public function setSApellido($sApellido)
    {
        $this->s_apellido = $sApellido;

        return $this;
    }

    /**
     * Get s_apellido
     *
     * @return string
     */
    public function getSApellido()
    {
        return $this->s_apellido;
    }

}