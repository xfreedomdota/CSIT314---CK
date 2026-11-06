<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../Control/13_20_39_LoginController.php';
require_once __DIR__ . '/../Control/14_21_40_LogOutController.php';
require_once __DIR__ . '/TestDatabase.php';

class RoleLoginTest extends TestCase
{
    private UserAccount $account;
    private UserProfile $profile;

    protected function setUp(): void
    {
        $_SESSION = [];
        $db = TestDatabase::create();
        $this->account = new UserAccount($db);
        $this->profile = new UserProfile($db);
        TestDatabase::addUser($db, 'designer', 'd-pass', 'Dee Signer', 'Interior Designer');
        TestDatabase::addUser($db, 'customer', 'c-pass', 'Cus Tomer', 'Customer');
        TestDatabase::addUser($db, 'platform', 'p-pass', 'Plat Form', 'Platform Manager');
    }

    private function controller(): LoginController
    {
        return new LoginController($this->account, $this->profile);
    }

    public function testDesignerLogin(): void
    {
        $this->assertTrue($this->controller()->login('designer', 'd-pass'));
        $this->assertSame('Interior Designer', $_SESSION['role']);
        $this->assertSame(1, $_SESSION['userId']);
    }

    public function testCustomerLogin(): void
    {
        $this->assertTrue($this->controller()->login('customer', 'c-pass'));
        $this->assertSame('Customer', $_SESSION['role']);
    }

    public function testPlatformManagerLogin(): void
    {
        $this->assertTrue($this->controller()->login('platform', 'p-pass'));
        $this->assertSame('Platform Manager', $_SESSION['role']);
    }

    public function testWrongPasswordIsRejected(): void
    {
        $this->assertFalse($this->controller()->login('designer', 'nope'));
        $this->assertArrayNotHasKey('role', $_SESSION);
    }

    public function testSuspendedProfileCannotLogin(): void
    {
        $this->profile->suspendProfile('customer');
        $this->assertFalse($this->controller()->login('customer', 'c-pass'));
    }

    public function testLogoutEndsSession(): void
    {
        $_SESSION = ['username' => 'designer', 'role' => 'Interior Designer'];
        $this->assertTrue((new LogOutController())->logout());
        $this->assertSame([], $_SESSION);
    }
}
