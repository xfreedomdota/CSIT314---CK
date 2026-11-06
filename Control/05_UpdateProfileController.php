<?php
require_once __DIR__ . '/../Entity/UserProfile.php';

class UpdateProfileController
{
    private UserProfile $userProfile;

    public function __construct(?UserProfile $userProfile = null)
    {
        $this->userProfile = $userProfile ?? new UserProfile();
    }

    public function updateProfile(string $username, string $password, string $name, string $userType): bool
    {
        if ($username === '' || $name === ''
            || !in_array($userType, UserProfile::USER_TYPES, true)) {
            return false;
        }
        return $this->userProfile->updateProfile($username, $password, $name, $userType);
    }
}
