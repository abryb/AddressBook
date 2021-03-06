<?php

namespace AddressBookBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * Contact
 *
 * @ORM\Table(name="contact")
 * @ORM\Entity(repositoryClass="AddressBookBundle\Repository\ContactRepository")
 */
class Contact
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="surname", type="string", length=255)
     */
    private $surname;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=2047, nullable=true)
     */
    private $description;

    /**
     * @var object
     *
     * @ORM\OneToMany(targetEntity="AddressBookBundle\Entity\Address", mappedBy="contact")
     */
    private $addresses;


    /**
     * @ORM\OneToMany(targetEntity="AddressBookBundle\Entity\Phone", mappedBy="contact")
     */
    private $phones;

    /**
     * @ORM\OneToMany(targetEntity="AddressBookBundle\Entity\Email", mappedBy="contact")
     */
    private $emails;

    /**
     * @ORM\ManyToMany(targetEntity="AddressBookBundle\Entity\ContactGroup", inversedBy="contacts")
     * @ORM\JoinTable(name="contact_contact_group")
     */
    private $groups;

    /**
     * @ORM\ManyToOne(targetEntity="AddressBookBundle\Entity\User", inversedBy="contacts")
     */
    private $user;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->groups = new ArrayCollection();
        $this->addresses = new ArrayCollection();
        $this->phones = new ArrayCollection();
        $this->emails = new ArrayCollection();
    }

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
     * Set name
     *
     * @param string $name
     * @return Contact
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set surname
     *
     * @param string $surname
     * @return Contact
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;

        return $this;
    }

    /**
     * Get surname
     *
     * @return string 
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Contact
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Add addresses
     *
     * @param Address $address
     * @return Contact
     * @internal param Address $addresses
     */
    public function addAddress(Address $address)
    {
        $this->addresses[] = $address;

        return $this;
    }

    /**
     * Remove addresses
     *
     * @param Address $addresses
     */
    public function removeAddress(Address $address)
    {
        $this->addresses->removeElement($address);
    }

    /**
     * Get addresses
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAddresses()
    {
        return $this->addresses;
    }

    /**
     * Add phones
     *
     * @param \AddressBookBundle\Entity\Phone $phones
     * @return Contact
     */
    public function addPhone(\AddressBookBundle\Entity\Phone $phone)
    {
        $this->phones[] = $phone;

        return $this;
    }

    /**
     * Remove phones
     *
     * @param \AddressBookBundle\Entity\Phone $phones
     */
    public function removePhone(\AddressBookBundle\Entity\Phone $phone)
    {
        $this->phones->removeElement($phone);
    }

    /**
     * Get phones
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPhones()
    {
        return $this->phones;
    }

    /**
     * Add email
     *
     * @param \AddressBookBundle\Entity\Email $email
     * @return Contact
     */
    public function addEmail(\AddressBookBundle\Entity\Email $email)
    {
        $this->emails[] = $email;

        return $this;
    }

    /**
     * Remove email
     *
     * @param \AddressBookBundle\Entity\Email $email
     */
    public function removeEmail(\AddressBookBundle\Entity\Email $email)
    {
        $this->emails->removeElement($email);
    }

    /**
     * Get email
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEmails()
    {
        return $this->emails;
    }

    /**
     * Add groups
     *
     * @param \AddressBookBundle\Entity\ContactGroup $groups
     * @return Contact
     */
    public function addGroup(\AddressBookBundle\Entity\ContactGroup $groups)
    {
        $this->groups[] = $groups;

        return $this;
    }

    /**
     * Remove groups
     *
     * @param \AddressBookBundle\Entity\ContactGroup $groups
     */
    public function removeGroup(\AddressBookBundle\Entity\ContactGroup $groups)
    {
        $this->groups->removeElement($groups);
    }

    /**
     * Get groups
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * Set user
     *
     * @param \AddressBookBundle\Entity\User $user
     * @return Contact
     */
    public function setUser(\AddressBookBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AddressBookBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
}
