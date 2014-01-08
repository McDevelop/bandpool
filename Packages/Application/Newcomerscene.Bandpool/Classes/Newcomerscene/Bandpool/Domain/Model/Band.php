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
class Band {

	/**
	 * @var string
	 */
	protected $name;
	
	/**
	 * @var string
	 */
	protected $genre;

	/**
	 * @var \Newcomerscene\Bandpool\Domain\Model\Image
	 * @ORM\ManyToOne(cascade={"persist"})
	 */
	protected $image;
	
	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @param string $name
	 * @return void
	 */
	public function setName($name) {
		$this->name = $name;
	}
	
	/**
	 * Getter for image
	 *
	 * @return \Newcomerscene\Bandpool\Domain\Model\Image
	 */
	public function getImage() {
		return $this->image;
	}

	/**
	 * Setter for image
	 *
	 * @param \Newcomerscene\Bandpool\Domain\Model\Image $image
	 */
	public function setImage(\Newcomerscene\Bandpool\Domain\Model\Image $image = NULL) {
			// work around property mapper delivering an empty Image
		if ($image === NULL || $image->getOriginalResource() !== NULL) {
			$this->image = $image;
		}
	}
	
	/**
	 * @return string
	 */
	public function getGenre() {
		return $this->genre;
	}

	/**
	 * @param string $genre
	 * @return void
	 */
	public function setGenre($genre) {
		$this->genre = $genre;
	}

}
?>