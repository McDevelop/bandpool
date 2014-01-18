<?php
namespace Newcomerscene\Bandpool\Domain\Model;

/*                                                                         *
 * This script belongs to the TYPO3 Flow package "Newcomerscene.Bandpool". *
 *                                                                         *
 *                                                                         */

use TYPO3\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

/**
 * @Flow\Entity
 */
class Song
{
    /**
     * @Flow\Identity
     * @var \DateTime
     */
    protected $crdate;

    /**
     * @var string	 
     */
    protected $description;

    /**
     * @var string	 
     */
    protected $title;

    /**
     * @var \TYPO3\Flow\Resource\Resource
     * @ORM\ManyToOne
     * FLOW3\Validate(type="NotEmpty")
     */
    protected $originalResource;

    /**
     * Constructs this status
     */
    public function __construct()
    {
        $this->crdate = new \DateTime();
    }

    /**
     * Setter for crdate
     *
     * @param \DateTime $crdate
     * @return void
     */
    public function setCrdate(\DateTime $crdate)
    {
        $this->crdate = $crdate;
    }

    /**
     * Getter for crdate
     *
     * @return \DateTime
     */
    public function getCrdate()
    {
        return $this->crdate;
    }

    /**
     * @return string
     */
    public function getDescrition()
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return void
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return void
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Sets the original resource
     *
     * @param \TYPO3\Flow\Resource\Resource $originalResource
     * @return void
     */
    public function setOriginalResource(\TYPO3\Flow\Resource\Resource $originalResource)
    {
        $this->originalResource = $originalResource;
    }

    /**
     * Returns the original resource
     *
     * @return \TYPO3\Flow\Resource\Resource $originalResource
     */
    public function getOriginalResource()
    {
        return $this->originalResource;
    }
}
