<?php
require_once __DIR__ . '/../Entity/UserProfile.php';

class SuspendProfileController
{
    private UserProfile $userProfile;

    public function __construct(?UserProfile $userProfile = null)
    {
        $this->userProfile = $userProfile ?? new UserProfile();
    }

    public function suspendProfile(string $username): bool
    {
        return $this->userProfile->suspendProfile($username);
    }
}
