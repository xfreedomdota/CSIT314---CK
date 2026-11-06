<?php
require_once __DIR__ . '/layout.php';
require_once __DIR__ . '/../Control/11_SuspendAccountController.php';

class SuspendAccountPage
{
    private string $message = '';
    private bool $success = false;

    public function displaySuspendPage(string $username): void
    {
        renderHeader('Suspend User Account');
        renderMessage($this->message, $this->success);
        echo '<form method="post">
                <label>Username</label><input name="username" value="' . e($username) . '" required>
                <button type="submit" class="danger">Suspend account</button>
              </form>';
        renderFooter();
    }

    public function submitSuspend(string $username): void
    {
        $controller = new SuspendAccountController();
        $this->success = $controller->suspendAccount($username);

        if ($this->success) {
            $this->message = 'User account suspended successfully.';
        } elseif ($controller->getError() === 'already_suspended') {
            $this->message = 'Account is already suspended';
        } else {
            $this->message = 'User account does not exist';
        }
        $this->displaySuspendPage($username);
    }
}

startSession('User Admin');
$page = new SuspendAccountPage();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $page->submitSuspend($_POST['username'] ?? '');
} else {
    $page->displaySuspendPage($_GET['username'] ?? '');
}
