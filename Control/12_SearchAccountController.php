<?php
require_once __DIR__ . '/../Entity/UserAccount.php';

class SearchAccountController
{
    private UserAccount $userAccount;

    public function __construct(?UserAccount $userAccount = null)
    {
        $this->userAccount = $userAccount ?? new UserAccount();
    }

    public function searchAccounts(string $keyword): array|false
    {
        $keyword = trim($keyword);
        if ($keyword === '') {
            return false;
        }
        return $this->userAccount->searchAccountsByKeyword($keyword);
    }
}
