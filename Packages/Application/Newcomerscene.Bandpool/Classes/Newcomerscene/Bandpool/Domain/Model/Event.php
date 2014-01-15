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
 */
class Event
{
    /**
	 * @Flow\Identity
	 * @var \DateTime
	 */
	protected $startDate;
	
	/**
	 * @Flow\Identity
	 * @var \DateTime
	 */
	protected $endDate;
	
	/**
	 * @var string	 
	 */
    protected $description;
	
	/**
	 * @var string	 
	 */
    protected $title;
	
	/**
     * @var \Newcomerscene\Bandpool\Domain\Model\Image
     * @ORM\ManyToOne(cascade={"persist"})
     */
    protected $image;
	
	/**
	 * Setter for startDate
	 *
	 * @param \DateTime $startDate
	 * @return void
	 */
	public function setStartDate(\DateTime $startDate) {
		$this->startDate = $startDate;
	}

	/**
	 * Getter for startDate
	 *
	 * @return \DateTime
	 */
	public function getStartDate() {
		return $this->startDate;
	}
	
	/**
	 * Setter for endDate
	 *
	 * @param \DateTime $endDate
	 * @return void
	 */
	public function setEndDate(\DateTime $endDate) {
		$this->endDate = $endDate;
	}
	
	/**
	 * Getter for endDate
	 *
	 * @return \DateTime
	 */
	public function getEndDate() {
		return $this->endDate;
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
}