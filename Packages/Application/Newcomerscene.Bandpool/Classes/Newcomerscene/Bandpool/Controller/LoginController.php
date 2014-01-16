<?php

namespace Newcomerscene\Bandpool\Controller;



use TYPO3\Flow\Annotations as Flow;

/**
 * A controller which allows for logging into the backend
 *
 */
class LoginController extends \TYPO3\Flow\Mvc\Controller\ActionController
{
    /**
     * @Flow\Inject
     * @var \TYPO3\Flow\Security\Authentication\AuthenticationManagerInterface
     */
    protected $authenticationManager;
	
	/**
     * @var \TYPO3\Flow\Security\Context
     * @Flow\Inject
     *
     */
    protected $securityContext;
	
	/**
     * @var Newcomerscene\Bandpool\Domain\Repository\ProfileRepository
     * @Flow\Inject
     */
    protected $profileRepository; 

	/**
     * initializeAction
     * @return void
     */
    /*public function initializeAction()
	{
        if($this->authenticationManager->isAuthenticated()===TRUE){
            $authenticationTokens = $this->securityContext->getAuthenticationTokensOfType('TYPO3\Flow\Security\Authentication\Token\UsernamePassword');
            if(count($authenticationTokens) === 1) {
                $account = $authenticationTokens[0]->getAccount();
                $this->profile = $this->profileRepository->findByAccount($account)->getFirst();
				$this->redirect("show", "Profile");
            }			
        }        
    }
	*/
    /**
     *
     *
     * @return string
     */
    public function showAction()
    {
        
    }

    /**
     * Authenticates an account by invoking the Provider based Authentication Manager.
     *
     * On successful authentication redirects to the list of posts, otherwise returns
     * to the login screen.
     *
     * @return void
     * @throws \TYPO3\Flow\Security\Exception\AuthenticationRequiredException
     */
    public function authenticateAction()
    {
        try {
            $this->authenticationManager->authenticate();			
            $authenticationTokens = $this->securityContext->getAuthenticationTokensOfType('TYPO3\Flow\Security\Authentication\Token\UsernamePassword');
            if(count($authenticationTokens) === 1) {
                $account = $authenticationTokens[0]->getAccount();
					
			    // Account vom User auslesen
				$accId = $account->getAccountIdentifier();
				$profileAcc = $this->profileRepository->findOneByName($accId);
					
				// Zur Identifizierung des Users
				$identifyCatgory = $profileAcc->getTypeAsString();
		
            }
            // View zum Backend des entsprechenden Users
		    $this->redirect("viewBackend", $identifyCatgory, 'Newcomerscene.Bandpool', array(lcfirst($identifyCatgory) => $profileAcc));					
            
        } catch (\TYPO3\Flow\Security\Exception\AuthenticationRequiredException $exception) {
            $this->addFlashMessage('Wrong username or password.');
            throw $exception;
			echo 'teddst';
				exit;
		    $this->redirect("show","Login");
        }
		
        
    }

    /**
     *
     * @return void
     */
    public function logoutAction()
    {
        $this->authenticationManager->logout();
        $this->addFlashMessage('Successfully logged out.');
        $this->redirect('show', 'Profile');
    }
}