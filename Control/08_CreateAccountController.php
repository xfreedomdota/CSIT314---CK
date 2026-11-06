<?php
require_once __DIR__ . '/../Entity/UserAccount.php';

class CreateAccountController
{
    private UserAccount $userAccount;
    private string $error = '';

    public function __construct(?UserAccount $userAccount = null)
    {
        $this->userAccount = $userAccount ?? new UserAccount();
    }

    public function getError(): string { return $this->error; }

    public function createAccount(string $username, string $password, string $email): bool
    {
        $this->error = '';

        if ($username === '' || $password === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error = 'invalid';
            return false;
        }
        if (!$this->userAccount->createAccount($username, $password, $email)) {
            $this->error = 'exists';
            return false;
        }
        return true;
    }
}
