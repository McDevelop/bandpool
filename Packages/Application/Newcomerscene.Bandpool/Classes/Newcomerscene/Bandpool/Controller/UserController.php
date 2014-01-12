<?php

namespace Newcomerscene\Bandpool\Controller;



use TYPO3\Flow\Annotations as Flow;


class UserController extends \TYPO3\Flow\Mvc\Controller\ActionController
{
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
     * @var Newcomerscene\Bandpool\Domain\Repository\UserRepository
     * @Flow\Inject
     */
    protected $userRepository;

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
        if ($this->authenticationManager->isAuthenticated()===FALSE) {
            $this->redirect("index", "Login");
        }

        $authenticationTokens = $this->securityContext->getAuthenticationTokensOfType('TYPO3\Flow\Security\Authentication\Token\UsernamePassword');
        if (count($authenticationTokens) === 1) {
            $account = $authenticationTokens[0]->getAccount();
            $this->user = $this->userRepository->findByAccount($account)->getFirst();
        }
    }

    public function initializeView(\TYPO3\Flow\Mvc\View\ViewInterface $view)
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
     * @param \Newcomerscene\Bandpool\Domain\Model\User $user
     * @return void
     */
    public function newAction(\Newcomerscene\Bandpool\Domain\Model\User $user=NULL)
    {
        $this->view->assign("user", $user);
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
    public function createAction($username, $pass, $pass2, $role, \Newcomerscene\Bandpool\Domain\Model\User $user)
    {
        if ($username == '' || strlen($username) < 3) {
            $this->addFlashMessage('Benutzername zu kurz');
            $this->redirect('new', 'User');
        } elseif ($pass == '' || $pass != $pass2) {
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
        if ( ((!empty($pass) && !empty($pass2))) ) {
            if ($pass != $pass2) {
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

        $this->userRepository->update($user);
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
        $this->userRepository->remove($user);
        $this->addFlashMessage('Der Benutzer '.$user->getAccount()->getAccountIdentifier().' wurde gelöscht.', "Benutzer gelöscht", "OK");
        $this->redirect("list");
    }
}
