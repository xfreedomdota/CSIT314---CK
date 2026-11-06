<?php
require_once __DIR__ . '/../Entity/UserProfile.php';

class CreateProfileController
{
    private UserProfile $userProfile;

    public function __construct(?UserProfile $userProfile = null)
    {
        $this->userProfile = $userProfile ?? new UserProfile();
    }

    public function createProfile(string $username, string $password, string $name, string $userType): bool
    {
        $username = trim($username);
        $name     = trim($name);

        if ($username === '' || $password === '' || $name === ''
            || !in_array($userType, UserProfile::USER_TYPES, true)) {
            return false;
        }
        return $this->userProfile->saveProfile($username, $password, $name, $userType);
    }
}
