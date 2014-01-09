<?php
namespace Newcomerscene\Bandpool\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Newcomerscene.Bandpool".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

class BandController extends \TYPO3\Flow\Mvc\Controller\ActionController
{
    /**
    * @var Newcomerscene\Bandpool\Domain\Repository\BandRepository
    * @Flow\Inject
    */
    protected $bandRepository;

    /**
     * @Flow\Inject
     * @var \TYPO3\Flow\Resource\ResourceManager
     */
    protected $resourceManager;

    /**
     * @return void
     */
    public function showAction()
    {
        $eineBand = $this->bandRepository->findOneByGenre("rock");
        $this->view->assign('band',$eineBand);

        $this->view->assign('foos', array(
            'bar', 'baz'
        ));
    }

    /**
    * Creates a new band
    *
    * @param \Newcomerscene\Bandpool\Domain\Model\Band $newImage The new band
    * @return void
    */
    public function createBandAction(\Newcomerscene\Bandpool\Domain\Model\Band $newBand)
    {
        $this->bandRepository->add($newBand);
        $this->redirect('show', 'Band');
    }

    /**
    * Creates a new image
    *
    * @param \Newcomerscene\Bandpool\Domain\Model\Band $band Edit the Bandphoto
    * @return void
    */
    public function editBandPhotoAction(\Newcomerscene\Bandpool\Domain\Model\Band $band)
    {
        #$image = $band->getImage();
        // Image wird hier noch bearbeitet per $band->getImage

        $this->bandRepository->update($band);
        $this->redirect('show', 'Band');
	}
}