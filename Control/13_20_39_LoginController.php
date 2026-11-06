<?php
require_once __DIR__ . '/../Entity/UserAccount.php';
require_once __DIR__ . '/../Entity/UserProfile.php';

class LoginController
{
    protected UserAccount $userAccount;
    protected UserProfile $userProfile;
    protected ?string $requiredRole = null;

    public function __construct(?UserAccount $userAccount = null, ?UserProfile $userProfile = null)
    {
        $this->userAccount = $userAccount ?? new UserAccount();
        $this->userProfile = $userProfile ?? new UserProfile();
    }

    public function login(string $username, string $password): bool
    {
        if (!$this->userAccount->validatePassword($username, $password)) {
            return false;
        }
        $profile = $this->userProfile->getProfile($username);
        if ($profile === null || $profile->getStatus() === 'Suspended') {
            return false;
        }
        if ($this->requiredRole !== null && $profile->getUserType() !== $this->requiredRole) {
            return false;
        }
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_regenerate_id(true);
        }
        $_SESSION['username'] = $username;
        $_SESSION['userId']   = $this->userAccount->searchAccount($username)->getUserId();
        $_SESSION['role']     = $profile->getUserType();
        $_SESSION['name']     = $profile->getName();
        return true;
    }
}
