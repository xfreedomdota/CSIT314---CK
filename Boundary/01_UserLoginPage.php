<?php
require_once __DIR__ . '/layout.php';
require_once __DIR__ . '/../Control/01_UserLoginController.php';

class UserLoginPage
{
    private string $message = '';

    public function displayPage(): void
    {
        renderHeader('User Admin Login', false);
        renderMessage($this->message, false);
        echo '<form method="post">
                <label>Username</label><input name="username" required>
                <label>Password</label><input name="password" type="password" required>
                <button type="submit">Log in</button>
              </form>';
        renderFooter();
    }

    public function submitLogin(string $username, string $password): void
    {
        $controller = new UserLoginController();

        if ($controller->login($username, $password)) {
            header('Location: Dashboard.php');
            exit;
        }
        $this->message = 'Invalid username or password. Please try again.';
        $this->displayPage();
    }
}

startSession();
$page = new UserLoginPage();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $page->submitLogin($_POST['username'] ?? '', $_POST['password'] ?? '');
} else {
    $page->displayPage();
}
