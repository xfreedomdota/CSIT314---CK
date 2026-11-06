<?php
require_once __DIR__ . '/../Entity/UserProfile.php';

class SearchProfileController
{
    private UserProfile $userProfile;

    public function __construct(?UserProfile $userProfile = null)
    {
        $this->userProfile = $userProfile ?? new UserProfile();
    }

    public function searchProfiles(string $filter): array
    {
        return $this->userProfile->searchProfilesByFilter(trim($filter));
    }
}
