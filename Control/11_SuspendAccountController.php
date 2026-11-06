<?php
require_once __DIR__ . '/../Entity/UserAccount.php';

class SuspendAccountController
{
    private UserAccount $userAccount;
    private string $error = '';

    public function __construct(?UserAccount $userAccount = null)
    {
        $this->userAccount = $userAccount ?? new UserAccount();
    }

    public function getError(): string { return $this->error; }

    public function suspendAccount(string $username): bool
    {
        $this->error = '';
        $account = $this->userAccount->searchAccount($username);

        if ($account === null) {
            $this->error = 'not_found';
            return false;
        }
        if ($account->getStatus() === 'Suspended') {
            $this->error = 'already_suspended';
            return false;
        }
        return $this->userAccount->suspendAccount($username);
    }
}
