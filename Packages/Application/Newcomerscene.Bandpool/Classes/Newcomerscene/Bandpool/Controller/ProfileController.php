<?php
namespace Newcomerscene\Bandpool\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Newcomerscene.Bandpool".*
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

class ProfileController extends \TYPO3\Flow\Mvc\Controller\ActionController
{
    /**
    * @var Newcomerscene\Bandpool\Domain\Repository\BandRepository
    * @Flow\Inject
    */
    protected $bandRepository;

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

}