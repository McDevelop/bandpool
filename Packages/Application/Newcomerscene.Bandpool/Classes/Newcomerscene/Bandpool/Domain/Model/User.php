<?php
namespace Newcomerscene\Bandpool\Domain\Model;

/*                                                                       *
 * This script belongs to the Flow package "Newcomerscene".              *
 *                                                                       *
 *                                                                       */

use TYPO3\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

/**
 * A User
 *
 * @Flow\Entity
 */
class User
{
    /**
    * The firstname
    * @Flow\Validate(type="NotEmpty")
    * @var string
    */
    protected $firstname;

    /**
    * The lastname
    * @Flow\Validate(type="NotEmpty")
    * @var string
    */
    protected $lastname;

    /**
    * The email
    * @Flow\Validate(type="NotEmpty")
    * @var string
    */
    protected $email;

    /**
    * The account
    * @ORM\ManyToOne(cascade={"all"})
    * @var \TYPO3\Flow\Security\Account
    */
    protected $account;

    /**
    * Get the Users firstname
    *
    * @return string The Users firstname
    */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
    * Sets this Users firstname
    *
    * @param string $firstname The Users firstname
    * @return void
    */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }

    /**
    * Get the Users lastname
    *
    * @return string The Users lastname
    */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
    * Sets this Users lastname
    *
    * @param string $lastname The Users lastname
    * @return void
    */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
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
}
