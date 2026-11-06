<?php
require_once __DIR__ . '/../Entity/UserAccount.php';

class ViewAccountController
{
    private UserAccount $userAccount;

    public function __construct(?UserAccount $userAccount = null)
    {
        $this->userAccount = $userAccount ?? new UserAccount();
    }

    public function getAccount(string $username): ?UserAccount
    {
        return $this->userAccount->searchAccount(trim($username));
    }
}
