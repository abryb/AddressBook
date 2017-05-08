<?php

namespace AddressBookBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * ContactGroup
 *
 * @ORM\Table(name="contact_group")
 * @ORM\Entity(repositoryClass="AddressBookBundle\Repository\ContactGroupRepository")
 */
class ContactGroup
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
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity="AddressBookBundle\Entity\Contact", mappedBy="groups")
     */
    private $contacts;

    /**
     * @ORM\ManyToOne(targetEntity="AddressBookBundle\Entity\User", inversedBy="groups")
     */
    private $user;

    public function __construct()
    {
        $this->contacts = new ArrayCollection();
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
     * @return ContactGroup
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
     * Add contacts
     *
     * @param \AddressBookBundle\Entity\Contact $contacts
     * @return ContactGroup
     */
    public function addContact(\AddressBookBundle\Entity\Contact $contacts)
    {
        $this->contacts[] = $contacts;

        return $this;
    }

    /**
     * Remove contacts
     *
     * @param \AddressBookBundle\Entity\Contact $contacts
     */
    public function removeContact(\AddressBookBundle\Entity\Contact $contacts)
    {
        $this->contacts->removeElement($contacts);
    }

    /**
     * Get contacts
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getContacts()
    {
        return $this->contacts;
    }

    /**
     * Set user
     *
     * @param \AddressBookBundle\Entity\User $user
     * @return ContactGroup
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
