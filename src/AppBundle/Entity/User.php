<?php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 */

class User extends BaseUser
{


    public function __construct()
    {
        parent::__construct();
        $this->registerDate = new \DateTime('now');
    }

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;


//    protected $email;



    /**
     * @ORM\Column(type="datetime")
     * @var DateTime
     */
    private $registerDate;

    /**
     * @var boolean
     *
     * @ORM\Column(name="subscribe", type="boolean", nullable=true)
     */
    private $notifications;

    /**
     * @var string
     *
     * @ORM\Column(name="api", type="string", length=50, nullable=true)
     */
    private $api;

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
     * Set registration date.
     *
     * @param DateTime $registerDate
     * @return User
     */
    public function setRegistrationDate($registerDate)
    {
        $this->registerDate = $registerDate;
    }

    /**
     * Get registration date.
     *
     * @return DateTime
     */
    public function getRegistrationDate()
    {
        return $this->registerDate;
    }

    /**
     * Set notifications.
     *
     * @param boolean $notifications
     * @return User
     */
    public function setNotifications($notifications)
    {
        $this->notifications = $notifications;
    }

    /**
     * Get notifications.
     *
     * @return boolean
     */
    public function getNotifications()
    {
        return $this->notifications;
    }

    /**
     * Set api.
     *
     * @param string $api
     * @return User
     */
    public function setApi($api)
    {
        $this->api = $api;
    }

    /**
     * Get api.
     *
     * @return string
     */
    public function getApi()
    {
        return $this->api;
    }


    /**
     * Set registerDate
     *
     * @param \DateTime $registerDate
     *
     * @return User
     */
    public function setRegisterDate($registerDate)
    {
        $this->registerDate = $registerDate;

        return $this;
    }

    /**
     * Get registerDate
     *
     * @return \DateTime
     */
    public function getRegisterDate()
    {
        return $this->registerDate;
    }

    /**
     * @param string $email
     * @return $this
     */
    public function setEmail($email)
    {
        $this->setUsername($email);
        $this->email = $email;
        return $this;
    }
}
