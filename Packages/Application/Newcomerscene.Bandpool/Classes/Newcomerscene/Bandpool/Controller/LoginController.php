<?php
namespace Newcomerscene\Bandpool\Controller;
  
  
  
use TYPO3\Flow\Annotations as Flow;

/**
 * LoginController
 *
 * Handles all stuff that has to do with the login
 */							
class LoginController extends \TYPO3\Flow\Mvc\Controller\ActionController {
  
    /**
	 * @Flow\Inject
     * @var \TYPO3\Flow\Security\Authentication\AuthenticationManagerInterface     
     */
    protected $authenticationManager;
  
    /**
	 * @Flow\Inject
     * @var \TYPO3\Flow\Security\AccountRepository    
     */
    protected $accountRepository;
  
    /**
	 * @Flow\Inject
     * @var \TYPO3\Flow\Security\AccountFactory
     */
    protected $accountFactory;
  
    /**
     * index action, does only display the form
     */
    public function indexAction() {		
        // do nothing, action only required to show form
    }
  
    /**
     * @throws \TYPO3\FLOW3\Security\Exception\AuthenticationRequiredException
     * @return void
     */
    public function authenticateAction() {
        try {
            $this->authenticationManager->authenticate();
            $this->flashMessageContainer->add('Successfully logged in.');
            $this->redirect('index', 'Login');
        } catch (\TYPO3\FLOW3\Security\Exception\AuthenticationRequiredException $exception) {
            $this->flashMessageContainer->add('Wrong username or password.');
            $this->flashMessageContainer->add($exception->getMessage());
            throw $exception;
        }
    }
  
    /**
     * @return void
     */
    public function registerAction() {	
        // do nothing more than display the register form
    }
  
    /**
     * save the registration
     * @param string $name
     * @param string $pass
     * @param string $pass2
     */
    public function createAction($name, $pass, $pass2) {	
        //$defaultRole = array(0 =>'Visitor');
  
        if($name == '' || strlen($name) < 3) {
            $this->flashMessageContainer->add('Username too short or empty');
            $this->redirect('register', 'Login');
        } else if($pass == '' || $pass != $pass2) {
            $this->flashMessageContainer->add('Password too short or does not match');
            $this->redirect('register', 'Login');
        } else {
			$identifier = $name;
			$password = $pass;
			$roles = 'Visitor';
			$authenticationProviderName = 'DefaultProvider';

			//$account = $this->accountFactory->createAccountWithPassword($identifier, $password, $roles, $authenticationProviderName);
			//$this->accountRepository->add($account);
		
		
            // create a account with password an add it to the accountRepository
            $account = $this->accountFactory->createAccountWithPassword($name, $pass, $roles,$authenticationProviderName);
		
            $this->accountRepository->add($account);
  
            // add a message and redirect to the login form
            $this->flashMessageContainer->add('Account created. Please login.');
            $this->redirect('index');
        }
  
        // redirect to the login form
        $this->redirect('index', 'Login');
    }
      
    public function logoutAction() {
        $this->authenticationManager->logout();
        $this->flashMessageContainer->add('Successfully logged out.');
        $this->redirect('index', 'Login');
    }
  
}