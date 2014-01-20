<?php
namespace Newcomerscene\Bandpool\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Newcomerscene.Bandpool".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

class BandController extends ProfileController
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
     * View im Backend f�r die Band
     *
     * @param \Newcomerscene\Bandpool\Domain\Model\Band $band
     * @return void
     */
    public function viewBackendAction(\Newcomerscene\Bandpool\Domain\Model\Band $band)
    {
        $this->view->assign('profile', $band);
    }

    /**
     * Editiert die Info der Band im Backend
     *
     * @param \Newcomerscene\Bandpool\Domain\Model\Band $band
     */
    public function editStandardInfoAction($band)
    {	    
        $this->view->assign('profile', $band);
    }

    /**
     * Creates a new band
     *
     * @param \TYPO3\Flow\Security\Account $account account
     * @param string $name Bandname
     * @return void
     */
    public function createBandAction(\TYPO3\Flow\Security\Account $account, $name)
    {
        $newBand = new \Newcomerscene\Bandpool\Domain\Model\Band();
        $newBand->setName($name);
        $newBand->setAccount($account);
        $newBand->setTypeAsString('Band');
        $this->bandRepository->add($newBand);

        // add a message and redirect to the login form
        $this->flashMessageContainer->addMessage(new \TYPO3\Flow\Error\Error('Account created. Please login.'));
        $this->redirect('show','Login');
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
