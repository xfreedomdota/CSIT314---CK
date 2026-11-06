<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../Control/02_UserLogOutController.php';

class UserSessionTest extends TestCase
{
    public function testLogoutTerminatesSession(): void
    {
        $_SESSION = ['username' => 'admin'];
        $controller = new UserLogOutController();

        $this->assertTrue($controller->logout());
        $this->assertSame([], $_SESSION);
    }
}
