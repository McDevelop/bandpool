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
class Band extends Profile
{
    /**
     * @var string
     * @ORM\Column(nullable=true)
     */
    protected $genre;
    
    /**
     * @var string
     * @ORM\Column(nullable=true)
     */
    protected $label;
    
    /**
     * @var string
     * @ORM\Column(nullable=true)
     */
    protected $vorbilder;

    /**
     * @return string
     */
    public function getGenre()
    {
        return $this->genre;
    }

    /**
     * @param string $genre
     * @return void
     */
    public function setGenre($genre)
    {
        $this->genre = $genre;
    }
    
    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param string $label
     * @return void
     */
    public function setLabel($label)
    {
        $this->label = $label;
    }
    
    /**
     * @return string
     */
    public function getVorbilder()
    {
        return $this->vorbilder;
    }

    /**
     * @param string $vorbilder
     * @return void
     */
    public function setVorbilder($vorbilder)
    {
        $this->vorbilder = $vorbilder;
    }
}