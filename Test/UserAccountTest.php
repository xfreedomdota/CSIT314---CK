<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/TestDatabase.php';
require_once __DIR__ . '/../Control/01_UserLoginController.php';
require_once __DIR__ . '/../Control/08_CreateAccountController.php';
require_once __DIR__ . '/../Control/09_ViewAccountController.php';
require_once __DIR__ . '/../Control/10_UpdateAccountController.php';
require_once __DIR__ . '/../Control/11_SuspendAccountController.php';
require_once __DIR__ . '/../Control/12_SearchAccountController.php';

class UserAccountTest extends TestCase
{
    private UserAccount $account;
    private UserProfile $profile;

    protected function setUp(): void
    {
        $_SESSION = [];
        $db = TestDatabase::create();
        $this->account = new UserAccount($db);
        $this->profile = new UserProfile($db);
        $this->account->createAccount('admin', 'admin123', 'admin@ck.com');
        $this->profile->saveProfile('admin', 'admin123', 'System Admin', 'User Admin');
    }

    public function testLoginWithValidCredentials(): void
    {
        $controller = new UserLoginController($this->account, $this->profile);
        $this->assertTrue($controller->login('admin', 'admin123'));
        $this->assertSame('admin', $_SESSION['username']);
        $this->assertSame('User Admin', $_SESSION['role']);
    }

    public function testCustomerCannotUseAdminLogin(): void
    {
        $this->account->createAccount('cust', 'pw', 'c@ck.com');
        $this->profile->saveProfile('cust', 'pw', 'Cust', 'Customer');
        $this->assertFalse((new UserLoginController($this->account, $this->profile))->login('cust', 'pw'));
    }

    public function testLoginWithWrongPassword(): void
    {
        $controller = new UserLoginController($this->account, $this->profile);
        $this->assertFalse($controller->login('admin', 'wrong'));
        $this->assertArrayNotHasKey('username', $_SESSION);
    }

    public function testSuspendedAccountCannotLogin(): void
    {
        $this->account->suspendAccount('admin');
        $this->assertFalse((new UserLoginController($this->account, $this->profile))->login('admin', 'admin123'));
    }

    public function testCreateAccountWithValidData(): void
    {
        $controller = new CreateAccountController($this->account);
        $this->assertTrue($controller->createAccount('john', 'pass123', 'john@mail.com'));
    }

    public function testCreateAccountWithInvalidEmail(): void
    {
        $controller = new CreateAccountController($this->account);
        $this->assertFalse($controller->createAccount('john', 'pass123', 'not-an-email'));
        $this->assertSame('invalid', $controller->getError());
    }

    public function testCreateAccountWithExistingUsername(): void
    {
        $controller = new CreateAccountController($this->account);
        $this->assertFalse($controller->createAccount('admin', 'x', 'other@ck.com'));
        $this->assertSame('exists', $controller->getError());
    }

    public function testPasswordIsStoredHashed(): void
    {
        $stored = $this->account->searchAccount('admin');
        $this->assertNotNull($stored);
        $this->assertTrue($this->account->validatePassword('admin', 'admin123'));
    }

    public function testViewExistingAccount(): void
    {
        $found = (new ViewAccountController($this->account))->getAccount('admin');
        $this->assertSame('admin@ck.com', $found->getEmail());
    }

    public function testViewMissingAccountReturnsNull(): void
    {
        $this->assertNull((new ViewAccountController($this->account))->getAccount('ghost'));
    }

    public function testUpdateAccountWithValidData(): void
    {
        $controller = new UpdateAccountController($this->account);
        $this->assertTrue($controller->updateAccount('admin', 'newpass', 'new@ck.com', 'Active'));
        $this->assertSame('new@ck.com', $this->account->searchAccount('admin')->getEmail());
        $this->assertTrue($this->account->validatePassword('admin', 'newpass'));
    }

    public function testUpdateAccountWithInvalidStatus(): void
    {
        $controller = new UpdateAccountController($this->account);
        $this->assertFalse($controller->updateAccount('admin', '', 'admin@ck.com', 'Deleted'));
    }

    public function testSuspendActiveAccount(): void
    {
        $controller = new SuspendAccountController($this->account);
        $this->assertTrue($controller->suspendAccount('admin'));
        $this->assertSame('Suspended', $this->account->searchAccount('admin')->getStatus());
    }

    public function testSuspendMissingAccount(): void
    {
        $controller = new SuspendAccountController($this->account);
        $this->assertFalse($controller->suspendAccount('ghost'));
        $this->assertSame('not_found', $controller->getError());
    }

    public function testSuspendAlreadySuspendedAccount(): void
    {
        $controller = new SuspendAccountController($this->account);
        $controller->suspendAccount('admin');
        $this->assertFalse($controller->suspendAccount('admin'));
        $this->assertSame('already_suspended', $controller->getError());
    }

    public function testSearchAccountsByKeyword(): void
    {
        $this->account->createAccount('designer1', 'p', 'd1@ck.com');
        $results = (new SearchAccountController($this->account))->searchAccounts('designer');
        $this->assertCount(1, $results);
        $this->assertSame('designer1', $results[0]->getUsername());
    }

    public function testSearchAccountsById(): void
    {
        $results = (new SearchAccountController($this->account))->searchAccounts('1');
        $this->assertSame('admin', $results[0]->getUsername());
    }

    public function testSearchAccountsNoMatch(): void
    {
        $this->assertSame([], (new SearchAccountController($this->account))->searchAccounts('zzz'));
    }

    public function testSearchAccountsEmptyInputIsInvalid(): void
    {
        $this->assertFalse((new SearchAccountController($this->account))->searchAccounts('   '));
    }
}
