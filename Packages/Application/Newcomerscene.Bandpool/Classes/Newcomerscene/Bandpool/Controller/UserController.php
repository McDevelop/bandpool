<?php
 
namespace Newcomerscene\Bandpool\Controller;
 
/*                                                                        *
 * This script belongs to the FLOW3 package "Kunden".                     *
 *                                                                        *
 *                                                                        */
 
use TYPO3\FLOW3\Annotations as FLOW3;
 
/**
 * Standard controller for the Kunden package
 *
 * @FLOW3\Scope("singleton")
 */
class UserController extends \TYPO3\FLOW3\Mvc\Controller\ActionController
{
 
    /**
     * @var \TYPO3\FLOW3\Security\Context
     * @FLOW3\Inject
     *
     */
    protected $securityContext;
       
    /**
     * @var \TYPO3\FLOW3\Security\Authentication\AuthenticationManagerInterface
     * @FLOW3\Inject
     */
    protected $authenticationManager;
       
   /**
     * @var \TYPO3\FLOW3\Security\AccountRepository
     * @FLOW3\Inject
     */
    protected $accountRepository;      
 
   /**
     * @var \Kunden\Domain\Repository\UserRepository
     * @FLOW3\Inject
     */
    protected $userRepository; 
 
    /**
     * @var \TYPO3\FLOW3\Security\AccountFactory
     * @FLOW3\Inject
     */
    protected $accountFactory; 
       
    /**
     * @var \TYPO3\FLOW3\Security\Cryptography\HashService
     * @FLOW3\Inject
     */
    protected $hashService;
       
    /**
     * @var \Kunden\Domain\Model\User
     */
    private $user;
       
       
 
    /**
     * initializeAction
     * @return void
     */
    public function initializeAction()
	{
        if($this->authenticationManager->isAuthenticated()===FALSE){
            $this->redirect("index", "Login");
        }
        $authenticationTokens = $this->securityContext->getAuthenticationTokensOfType('TYPO3\FLOW3\Security\Authentication\Token\UsernamePassword');
            if(count($authenticationTokens) === 1) {
                $account = $authenticationTokens[0]->getAccount();
                $this->user = $this->userRepository->findByAccount($account)->getFirst();
            }
        }
       
    public function initializeView(\TYPO3\FLOW3\Mvc\View\ViewInterface $view)
	{
        $view->assign('userinfo', $this->user);
        $view->assign("navIndex", 3);
    }      
 
    /**
     * Index action
     *
     * @return void
     */
    public function indexAction()
	{
 
    }
       
    /**
     * List action
     *
     * @return void
     */
    public function listAction()
	{
        $users = $this->userRepository->findAll();
        $this->view->assign("users", $users);
    }
       
    /**
     * newAction
     * @param \Kunden\Domain\Model\User $user
     * @return void
     */
    public function newAction(\Kunden\Domain\Model\User $user=NULL)
	{
        $this->view->assign("user", $user);
    }
       
    /**
     * createAction
     * @param string $username
     * @param string $pass
     * @param string $pass2
     * @param string $role
     * @param \Kunden\Domain\Model\User $user
     * @return void
     *
     */
    public function createAction($username, $pass, $pass2, $role, \Kunden\Domain\Model\User $user)
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
            $this->userRepository->add($user);  
            // add a message and redirect to the login form
            $this->addFlashMessage('Benutzer wurde erstellt.');
            $this->redirect('list');
        }
               
                       
        //$this->userRepository->add($user);
        //$this->addFlashMessage("Der neue Benutzer ".$user->getFirstname()." wurde gespeichert.", "Speichern OK", "OK");
        //$this->redirect("list");
        }      
       
        /**
         * editAction
         * @param \Kunden\Domain\Model\User $user
         * @return void
         */
        public function editAction(\Kunden\Domain\Model\User $user){
                $this->view->assign("user", $user);
        }      
       
        /**
         * updateAction
         * @param \Kunden\Domain\Model\User $user
         * @param string $pass
         * @param string $pass2
         * @param string $role
         * @return void
         */
        public function updateAction(\Kunden\Domain\Model\User $user, $pass, $pass2, $role){
                if( ((!empty($pass) && !empty($pass2)))  ){
                        if($pass != $pass2){
                                $this->addFlashMessage('Die Passwörter stimmen nicht überein', "Fehler", "Error");
                                $this->redirect("edit", NULL, NULL, array("user" => $user));
                        } else {
                                $roles = array();
                                foreach (array($role) as $r) {
                                        $roles[] = new \TYPO3\FLOW3\Security\Policy\Role($r);
                                }
               
                                $account = $this->accountRepository->findByAccountIdentifierAndAuthenticationProviderName($user->getAccount()->getAccountIdentifier(),"DefaultProvider");
                                $account->setCredentialsSource($this->hashService->hashPassword($pass, "default"));
                                $account->setRoles($roles);
                                $this->accountRepository->update($account);
                                $this->addFlashMessage('Account wurde gespeichert', "Speichern OK", "OK");
                        }
                }
               
                $this->userRepository->update($user);
                $this->addFlashMessage('Benutzerprofil '. $user->getAccount()->getAccountIdentifier() .' wurde gespeichert.');
                $this->redirect("edit", NULL, NULL, array("user" => $user));
        }
 
        /**
         * deleteAction
         * @param \Kunden\Domain\Model\User $user
         * @return void
         */
        public function deleteAction(\Kunden\Domain\Model\User $user){
                $this->userRepository->remove($user);
                $this->addFlashMessage('Der Benutzer '.$user->getAccount()->getAccountIdentifier().' wurde gelöscht.', "Benutzer gelöscht", "OK");
                $this->redirect("list");
        }      
 
}
 
?>