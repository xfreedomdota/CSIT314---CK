<?php
require_once __DIR__ . '/layout.php';
require_once __DIR__ . '/../Control/08_CreateAccountController.php';

class CreateAccountPage
{
    private string $message = '';
    private bool $success = false;
    private string $username = '';

    public function displayCreatePage(): void
    {
        renderHeader('Create User Account');
        renderMessage($this->message, $this->success);
        if ($this->success) {
            echo '<p><a class="btn light" href="03_CreateProfilePage.php?username=' . urlencode($this->username) . '">Next: create the user profile (role) for ' . e($this->username) . '</a></p>';
        }
        echo '<form method="post">
                <label>Username</label><input name="username" required value="' . e($_GET['username'] ?? '') . '">
                <label>Password</label><input name="password" type="password" required>
                <label>Email</label><input name="email" type="email" required>
                <button type="submit">Create account</button>
              </form>';
        renderFooter();
    }

    public function submitCreateAccount(string $username, string $password, string $email): void
    {
        $this->username = $username;
        $controller = new CreateAccountController();
        $this->success = $controller->createAccount($username, $password, $email);

        if ($this->success) {
            $this->message = 'User account created successfully.';
        } elseif ($controller->getError() === 'exists') {
            $this->message = 'Username already exists';
        } else {
            $this->message = 'Invalid or missing data. Please correct it.';
        }
        $this->displayCreatePage();
    }
}

startSession('User Admin');
$page = new CreateAccountPage();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $page->submitCreateAccount($_POST['username'] ?? '', $_POST['password'] ?? '', $_POST['email'] ?? '');
} else {
    $page->displayCreatePage();
}
