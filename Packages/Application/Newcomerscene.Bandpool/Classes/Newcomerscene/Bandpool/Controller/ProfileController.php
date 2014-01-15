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
     * @var \TYPO3\Flow\Security\Context
     * @Flow\Inject
     *
     */
    protected $securityContext;
       
    /**
     * @var \TYPO3\Flow\Security\Authentication\AuthenticationManagerInterface
     * @Flow\Inject
     */
    protected $authenticationManager;
       
   /**
     * @var \TYPO3\Flow\Security\AccountRepository
     * @Flow\Inject
     */
    protected $accountRepository;      	
 
   /**
     * @var Newcomerscene\Bandpool\Domain\Repository\ProfileRepository
     * @Flow\Inject
     */
    protected $profileRepository; 
 
    /**
     * @var \TYPO3\Flow\Security\AccountFactory
     * @Flow\Inject
     */
    protected $accountFactory; 
       
    /**
     * @var \TYPO3\Flow\Security\Cryptography\HashService
     * @Flow\Inject
     */
    protected $hashService;
       
    /**
     * @var Newcomerscene\Bandpool\Domain\Model\User
     */
    private $user;
       
       
 
    /**
     * initializeAction
     * @return void
     */
    public function initializeAction()
	{
        if($this->authenticationManager->isAuthenticated()===TRUE){
            $authenticationTokens = $this->securityContext->getAuthenticationTokensOfType('TYPO3\Flow\Security\Authentication\Token\UsernamePassword');
            if(count($authenticationTokens) === 1) {
                $account = $authenticationTokens[0]->getAccount();
                $this->user = $this->profileRepository->findByAccount($account)->getFirst();
				$this->redirect("show", "Profile");
            }			
        }
        
    }
       
    public function initializeView(\TYPO3\Flow\Mvc\View\ViewInterface $view)
	{
        $view->assign('userinfo', $this->user);
        $view->assign("navIndex", 3);
    }      
 
    /**
     * @return void
     */
    public function showAction()
    {
        //$eineBand = $this->bandRepository->findOneByGenre("rock");
        //$this->view->assign('band',$eineBand);

        $this->view->assign('foos', array(
            'bar', 'baz'
        ));
    }
       
    /**
     * List action
     *
     * @return void
     */
    public function listAction()
	{
        $users = $this->profileRepository->findAll();
        $this->view->assign("users", $users);
    }
       
    /**
     * newAction
     * @param \Newcomerscene\Bandpool\Domain\Model\User $user
     * @return void
     */
    public function newAction(\Newcomerscene\Bandpool\Domain\Model\User $user=NULL)
	{
        $this->view->assign("user", $user);
    }
	
	/**
     * @return void
     */
    public function registerAction() {
        // do nothing more than display the register form
		
		
		
    }
       
    /**
     * createAction
     * @param string $username
     * @param string $pass
     * @param string $pass2
     * @param string $role
     * @param \Newcomerscene\Bandpool\Domain\Model\User $user
     * @return void
     *
     */
    /*public function createAction($username, $pass, $pass2, $role, \Newcomerscene\Bandpool\Domain\Model\User $user)
	{
               
        if($username == '' || strlen($username) < 3) {
            $this->addFlashMessage('Benutzername zu kurz');
            $this->redirect('new', 'User');
        } else if($pass == '' || $pass != $pass2) {
            $this->addFlashMessage('Passwort leer oder stimmt nicht überein');
            $this->redirect('new', 'User');
        } else {
 
            // create a account with password an add it to the accountRepository
            $account = $this->accountFactory->createAccountWithPassword($username, $pass, array($role));
            $this->accountRepository->add($account);
            $user->setAccount($account);
            $this->profileRepository->add($user);  
            // add a message and redirect to the login form
            $this->addFlashMessage('Benutzer wurde erstellt.');
            $this->redirect('list');
        }
               
                       
        //$this->profileRepository->add($user);
        //$this->addFlashMessage("Der neue Benutzer ".$user->getFirstname()." wurde gespeichert.", "Speichern OK", "OK");
        //$this->redirect("list");
    }      
    */
	/**
     * save the registration
     * @param string $name
     * @param string $category
     * @param string $pass
     * @param string $pass2
     */
    public function createAction($name, $category, $pass, $pass2) {		
        
		switch ($category) {
		    case 'Band' : $role = 'Newcomerscene.Bandpool:Visitor';
            break;
            default: $role = 'Newcomerscene.Bandpool:Visitor';			
		}       
 
        // Prüfe Name und Passwort
        if($name == '' || strlen($name) < 3) {
            $this->flashMessageContainer->addMessage(new \TYPO3\Flow\Error\Error('Username too short or empty'));
            $this->redirect('register', 'User');
        } else if($pass == '' || $pass != $pass2) {
            $this->flashMessageContainer->addMessage(new \TYPO3\Flow\Error\Error('Password too short or does not match'));
            $this->redirect('register', 'User');
        } else {
 
            // create an account with password an add it to the accountRepository
            $account = $this->accountFactory->createAccountWithPassword($name, $pass, array($role));
            $this->accountRepository->add($account);
			$this->persistenceManager->persistAll();
			
			// add a profile in its category
			$this->forward('createBand', $category, 'Newcomerscene.Bandpool', array("account" => $account, "name" => $name));	
            
        }
 
        // redirect to the login form		
		$this->redirect('register', 'Profile');
    }

	
    /**
     * editAction
     * @param \Newcomerscene\Bandpool\Domain\Model\User
     * @return void
     */
    public function editAction(\Newcomerscene\Bandpool\Domain\Model\User $user)
	{
        $this->view->assign("user", $user);
    }    
       
    /**
     * updateAction
     * @param \Newcomerscene\Bandpool\Domain\Model\User $user
     * @param string $pass
     * @param string $pass2
     * @param string $role
     * @return void
     */
    public function updateAction(\Newcomerscene\Bandpool\Domain\Model\User $user, $pass, $pass2, $role)
	{
        if( ((!empty($pass) && !empty($pass2)))  ){
            if($pass != $pass2){
                $this->addFlashMessage('Die Passwörter stimmen nicht überein', "Fehler", "Error");
                $this->redirect("edit", NULL, NULL, array("user" => $user));
            } else {
                $roles = array();
                foreach (array($role) as $r) {
                    $roles[] = new \TYPO3\Flow\Security\Policy\Role($r);
                }
               
                $account = $this->accountRepository->findByAccountIdentifierAndAuthenticationProviderName($user->getAccount()->getAccountIdentifier(),"DefaultProvider");
                $account->setCredentialsSource($this->hashService->hashPassword($pass, "default"));
                $account->setRoles($roles);
                $this->accountRepository->update($account);
                $this->addFlashMessage('Account wurde gespeichert', "Speichern OK", "OK");
            }
        }
               
        $this->profileRepository->update($user);
        $this->addFlashMessage('Benutzerprofil '. $user->getAccount()->getAccountIdentifier() .' wurde gespeichert.');
        $this->redirect("edit", NULL, NULL, array("user" => $user));
    }
 
    /**
     * deleteAction
     * @param \Newcomerscene\Bandpool\Domain\Model\User $user
     * @return void
     */
    public function deleteAction(\Newcomerscene\Bandpool\Domain\Model\User $user)
	{
        $this->profileRepository->remove($user);
        $this->addFlashMessage('Der Benutzer '.$user->getAccount()->getAccountIdentifier().' wurde gelöscht.', "Benutzer gelöscht", "OK");
        $this->redirect("list");
    }
}