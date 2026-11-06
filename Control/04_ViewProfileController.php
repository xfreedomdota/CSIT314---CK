<?php
require_once __DIR__ . '/../Entity/UserProfile.php';

class ViewProfileController
{
    private UserProfile $userProfile;

    public function __construct(?UserProfile $userProfile = null)
    {
        $this->userProfile = $userProfile ?? new UserProfile();
    }

    public function getProfile(string $username): ?UserProfile
    {
        return $this->userProfile->getProfile(trim($username));
    }
}
