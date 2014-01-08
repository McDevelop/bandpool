<?php
namespace Newcomerscene\Bandpool\Controller;

use TYPO3\Flow\Annotations as Flow;

/**
 * The account controller for the Blog package
 *
 */
class AccountController extends \TYPO3\Flow\Mvc\Controller\ActionController {

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Security\AccountRepository
	 */
	protected $accountRepository;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Security\Authentication\AuthenticationManagerInterface
	 */
	protected $authenticationManager;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Security\Context
	 */
	protected $securityContext;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Security\Cryptography\HashService
	 */
	protected $hashService;

	/**
	 * List action for this controller.
	 *
	 * @return string
	 */
	public function indexAction() {
		$this->forward('edit');
	}

	/**
	 * Displays a form for setting a new password and / or username
	 *
	 * @return string An HTML form for editing the account properties
	 */
	public function editAction() {
		$activeTokens = $this->securityContext->getAuthenticationTokens();
		foreach ($activeTokens as $token) {
			if ($token->isAuthenticated()) {
				$account = $token->getAccount();
				$this->view->assign('account', $account);
				break;
			}
		}
	}

	/**
	 * @return void
	 */
	public function initializeUpdateAction() {
		$this->arguments['account']->getPropertyMappingConfiguration()->setTargetTypeForSubProperty('party', 'TYPO3\Party\Domain\Model\Person');
	}

	/**
	 * Updates the account properties
	 *
	 * @param \TYPO3\Flow\Security\Account $account
	 * @param string $password
	 * @return void
	 */
	public function updateAction(\TYPO3\Flow\Security\Account $account, $password = '') {
		if ($password != '') {
			$account->setCredentialsSource($this->hashService->hashPassword($password));
		}

		$this->accountRepository->update($account);
		$this->partyRepository->update($account->getParty());
		$this->addFlashMessage('Your account details have been updated.');
		$this->redirect('index', 'Admin');
	}
}
?>