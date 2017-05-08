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

    public function __construct()
    {
        parent::__construct();
        $this->contacts = new ArrayCollection();
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
}
