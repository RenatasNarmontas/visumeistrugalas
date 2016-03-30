<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 */

class User
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=50)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=64)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", length=50)
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=50)
     */
    private $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=50)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=50)
     */
    private $telephoneNumber;
    /**
     * @ORM\Column(type="datetime")
     * @var DateTime
     */
    private $registerDate;

    /**
     * @var boolean
     *
     * @ORM\Column(name="subscribe", type="boolean")
     */
    private $subscription;

    /**
     * @var string
     *
     * @ORM\Column(name="api", type="string", length=50)
     */
    private $api;

    /**
     * @ORM\OneToOne(targetEntity="Role")
     * @ORM\JoinColumn(name="role_id", referencedColumnName="id")
     */
    private $role;

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
     * Set username
     *
     * @param string $username
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * Get username.
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set password.
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = password_hash($password, PASSWORD_BCRYPT) ;
    }

    /**
     * Get password.
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set first name.
     *
     * @param string $firstName
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * Get first name.
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set last name.
     *
     * @param string $lastName
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * Get last name.
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set email.
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Get email.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Get telephone number.
     *
     * @return string
     */
    public function getTelephoneNumber()
    {
        return $this->telephoneNumber;
    }

    /**
     * Set telephone number.
     *
     * @param string $telephoneNumber
     * @return User
     */
    public function setTelephoneNumber($telephoneNumber)
    {
        $this->telephoneNumber = $telephoneNumber;
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
     * Set subscription.
     *
     * @param boolean $subscription
     * @return User
     */
    public function setSubscribtion($subscription)
    {
        $this->subscription = $subscription;
    }

    /**
     * Get subscription.
     *
     * @return boolean
     */
    public function getSubscription()
    {
        return $this->subscription;
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
     * Set role.
     *
     * @param $role
     * @return User
     */
    public function setRole($role)
    {
        $this->role = $role;
    }

    /**
     * Get role.
     *
     * @return Role
     */
    public function getRole()
    {
        return $this->role;
    }
}
