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
class Status
{
    /**
     * @var \Newcomerscene\Bandpool\Domain\Model\Image
     * @ORM\ManyToOne(cascade={"persist"})
     */
    protected $image;

    /**
     * @var string
     * @ORM\OrderBy({"date" = "DESC"})
     */
    protected $content;

    /**
     * @Flow\Identity
     * @var \DateTime
     */
    protected $date;

    /**
     * Constructs this status
     */
    public function __construct()
    {
        $this->date = new \DateTime();
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
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     * @return void
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * Setter for date
     *
     * @param \DateTime $date
     * @return void
     */
    public function setDate(\DateTime $date)
    {
        $this->date = $date;
    }

    /**
     * Getter for date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }
}
