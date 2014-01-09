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
            $this->redirect('show', 'Login');
        } catch (\TYPO3\Flow\Security\Exception\AuthenticationRequiredException $exception) {
            $this->addFlashMessage('Wrong username or password.');
            throw $exception;
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
        $this->redirect('show', 'Band');
    }
}