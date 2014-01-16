<?php
namespace Newcomerscene\Bandpool\Domain\Model;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Newcomerscene.Bandpool".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

/**
 * @Flow\Entity 
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({"profile" = "Profile", "Band" = "Band"})
 */
class Profile
{

    /**
     * @var string
	 * @ORM\Column(nullable=true)
     */
    protected $country;
	
	/**
     * @var string
	 * @ORM\Column(nullable=true)
     */
    protected $firstName;
	
	/**
     * @var string
	 * @ORM\Column(nullable=true)
     */
    protected $lastName;
	
    /**
     * @var string
	 * @ORM\Column(nullable=true)
     */
    protected $name;   

    /**
     * @var \Newcomerscene\Bandpool\Domain\Model\Image
     * @ORM\ManyToOne(cascade={"persist"})
     */
    protected $image;
	
	/**
     * @var \Newcomerscene\Bandpool\Domain\Model\Status
     * @ORM\ManyToOne(cascade={"persist"})
     */
    protected $status;
	
	/**
     * @var \Newcomerscene\Bandpool\Domain\Model\Event
     * @ORM\ManyToOne(cascade={"persist"})
     */
    protected $events;
	
	/**
     * @var \Newcomerscene\Bandpool\Domain\Model\Song
     * @ORM\ManyToOne(cascade={"persist"})
     */
    protected $songs;
	
	/**
    * The account
    * @ORM\ManyToOne(cascade={"all"})
    * @var \TYPO3\Flow\Security\Account
    */
    protected $account;
	
	/**
    * The email    
    * @var string
	* @ORM\Column(nullable=true)
    */
    protected $email;	
	
	/**
    * The typeAsString    
    * @var string
	* @ORM\Column(nullable=true)
    */
    protected $typeAsString;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return void
     */
    public function setName($name)
    {
        $this->name = $name;
    }
	
	/**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param string $country
     * @return void
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }
	
	/**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     * @return void
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }
	
	/**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     * @return void
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * Getter for image
     *
     * @return \Newcomerscene\Bandpool\Domain\Model\Image
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Setter for image
     *
     * @param \Newcomerscene\Bandpool\Domain\Model\Image $image
     */
    public function setImage(\Newcomerscene\Bandpool\Domain\Model\Image $image = NULL)
    {
        // work around property mapper delivering an empty Image
        if ($image === NULL || $image->getOriginalResource() !== NULL) {
            $this->image = $image;
        }
    }
	
	/**
     * Getter for status
     *
     * @return \Newcomerscene\Bandpool\Domain\Model\Status
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Setter for status
     *
     * @param \Newcomerscene\Bandpool\Domain\Model\Status $status
     */
    public function setStatus(\Newcomerscene\Bandpool\Domain\Model\Status $status = NULL)
    {      
        $this->status = $status;        
    }
	
	/**
     * Getter for events
     *
     * @return \Newcomerscene\Bandpool\Domain\Model\Event
     */
    public function getEvents()
    {
        return $this->events;
    }

    /**
     * Setter for events
     *
     * @param \Newcomerscene\Bandpool\Domain\Model\Event $events
     */
    public function setEvents(\Newcomerscene\Bandpool\Domain\Model\Event $events = NULL)
    {      
        $this->events = $events;        
    }
	
	/**
     * Getter for songs
     *
     * @return \Newcomerscene\Bandpool\Domain\Model\Song
     */
    public function getSongs()
    {
        return $this->songs;
    }

    /**
     * Setter for songs
     *
     * @param \Newcomerscene\Bandpool\Domain\Model\Song $songs
     */
    public function setSongs(\Newcomerscene\Bandpool\Domain\Model\Event $songs = NULL)
    {      
        $this->songs = $songs;        
    }
	
	/**
     * Sets (and adds if necessary) the account.
     *
     * @param \TYPO3\Flow\Security\Account $account
     * @return void
    */
    public function setAccount(\TYPO3\Flow\Security\Account $account)
    {
        $this->account = $account;
    }

    /**
     * Returns the account, if one has been defined.
     *
     * @return \TYPO3\Flow\Security\Account $account
    */
    public function getAccount()
    {
        return $this->account;
    }
	
	/**
     * Get the Users email
     *
     * @return string The Users email
    */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Sets this Users email
     *
     * @param string $email The Users email
     * @return void
    */
    public function setEmail($email)
    {
        $this->email = $email;
    }
	
	/**
     * Get the Users typeAsString
     *
     * @return string The Users typeAsString
    */
    public function getTypeAsString()
    {
        return $this->typeAsString;
    }
	
	/**
     * Sets this Users typeAsString
     *
     * @param string $typeAsString The Users typeAsString
     * @return void
    */
    public function setTypeAsString($typeAsString)
    {
        $this->typeAsString = $typeAsString;
    }
}