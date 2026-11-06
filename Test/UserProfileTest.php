<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/TestDatabase.php';
require_once __DIR__ . '/../Control/03_CreateProfileController.php';
require_once __DIR__ . '/../Control/04_ViewProfileController.php';
require_once __DIR__ . '/../Control/05_UpdateProfileController.php';
require_once __DIR__ . '/../Control/06_SuspendProfileController.php';
require_once __DIR__ . '/../Control/07_SearchProfileController.php';

class UserProfileTest extends TestCase
{
    private UserProfile $profile;

    protected function setUp(): void
    {
        $this->profile = new UserProfile(TestDatabase::create());
        $this->profile->saveProfile('alice', 'pass', 'Alice Tan', 'Interior Designer');
    }

    public function testCreateProfileWithValidData(): void
    {
        $controller = new CreateProfileController($this->profile);
        $this->assertTrue($controller->createProfile('bob', 'pass', 'Bob Lee', 'Customer'));
    }

    public function testCreateProfileWithInvalidUserType(): void
    {
        $controller = new CreateProfileController($this->profile);
        $this->assertFalse($controller->createProfile('bob', 'pass', 'Bob Lee', 'Hacker'));
    }

    public function testCreateProfileWithMissingName(): void
    {
        $controller = new CreateProfileController($this->profile);
        $this->assertFalse($controller->createProfile('bob', 'pass', '  ', 'Customer'));
    }

    public function testCreateDuplicateProfile(): void
    {
        $controller = new CreateProfileController($this->profile);
        $this->assertFalse($controller->createProfile('alice', 'pass', 'Alice Again', 'Customer'));
    }

    public function testViewExistingProfile(): void
    {
        $found = (new ViewProfileController($this->profile))->getProfile('alice');
        $this->assertSame('Alice Tan', $found->getName());
        $this->assertSame('Interior Designer', $found->getUserType());
    }

    public function testViewMissingProfileReturnsNull(): void
    {
        $this->assertNull((new ViewProfileController($this->profile))->getProfile('ghost'));
    }

    public function testUpdateProfileWithValidData(): void
    {
        $controller = new UpdateProfileController($this->profile);
        $this->assertTrue($controller->updateProfile('alice', '', 'Alice Wong', 'Platform Manager'));
        $updated = $this->profile->getProfile('alice');
        $this->assertSame('Alice Wong', $updated->getName());
        $this->assertSame('Platform Manager', $updated->getUserType());
    }

    public function testUpdateMissingProfile(): void
    {
        $controller = new UpdateProfileController($this->profile);
        $this->assertFalse($controller->updateProfile('ghost', '', 'Nobody', 'Customer'));
    }

    public function testSuspendExistingProfile(): void
    {
        $this->assertTrue((new SuspendProfileController($this->profile))->suspendProfile('alice'));
        $this->assertSame('Suspended', $this->profile->getProfile('alice')->getStatus());
    }

    public function testSuspendMissingProfile(): void
    {
        $this->assertFalse((new SuspendProfileController($this->profile))->suspendProfile('ghost'));
    }

    public function testSearchProfilesByUserType(): void
    {
        $this->profile->saveProfile('carl', 'pass', 'Carl Ng', 'Customer');
        $results = (new SearchProfileController($this->profile))->searchProfiles('Designer');
        $this->assertCount(1, $results);
        $this->assertSame('alice', $results[0]->getUsername());
    }

    public function testSearchProfilesNoMatch(): void
    {
        $this->assertSame([], (new SearchProfileController($this->profile))->searchProfiles('zzz'));
    }
}
