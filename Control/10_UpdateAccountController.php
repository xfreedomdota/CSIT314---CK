<?php
require_once __DIR__ . '/../Entity/UserAccount.php';

class UpdateAccountController
{
    public const STATUSES = ['Active', 'Suspended'];

    private UserAccount $userAccount;

    public function __construct(?UserAccount $userAccount = null)
    {
        $this->userAccount = $userAccount ?? new UserAccount();
    }

    public function updateAccount(string $username, string $password, string $email, string $status): bool
    {
        $email = trim($email);

        if (trim($username) === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)
            || !in_array($status, self::STATUSES, true)) {
            return false;
        }
        return $this->userAccount->updateAccount(trim($username), $password, $email, $status);
    }
}
