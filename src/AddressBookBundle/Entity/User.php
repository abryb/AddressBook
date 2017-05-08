<?php

namespace AddressBookBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="ab_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="AddressBookBundle\Entity\Contact", mappedBy="user")
     */
    protected $contacts;

    /**
     * @ORM\OneToMany(targetEntity="AddressBookBundle\Entity\ContactGroup", mappedBy="user")
     */
    protected $contactGroups;

    public function __construct()
    {
        parent::__construct();
        $this->contacts = new ArrayCollection();
        $this->contactGroups = new ArrayCollection();
    }

    /**
     * Add contacts
     *
     * @param \AddressBookBundle\Entity\Contact $contacts
     * @return User
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
     * Add contactGroups
     *
     * @param \AddressBookBundle\Entity\ContactGroup $contactGroups
     * @return User
     */
    public function addContactGroup(\AddressBookBundle\Entity\ContactGroup $contactGroups)
    {
        $this->contactGroups[] = $contactGroups;

        return $this;
    }

    /**
     * Remove contactGroups
     *
     * @param \AddressBookBundle\Entity\ContactGroup $contactGroups
     */
    public function removeContactGroup(\AddressBookBundle\Entity\ContactGroup $contactGroups)
    {
        $this->contactGroups->removeElement($contactGroups);
    }

    /**
     * Get contactGroups
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getContactGroups()
    {
        return $this->contactGroups;
    }
}
