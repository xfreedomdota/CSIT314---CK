<?php
require_once __DIR__ . '/layout.php';
require_once __DIR__ . '/../Control/06_SuspendProfileController.php';

class SuspendProfilePage
{
    private string $message = '';
    private bool $success = false;

    public function displaySuspendPage(string $username): void
    {
        renderHeader('Suspend User Profile');
        renderMessage($this->message, $this->success);
        echo '<form method="post">
                <label>Username</label><input name="username" value="' . e($username) . '" required>
                <button type="submit" class="danger">Suspend profile</button>
              </form>';
        renderFooter();
    }

    public function submitSuspend(string $username): void
    {
        $controller = new SuspendProfileController();
        $this->success = $controller->suspendProfile($username);
        $this->message = $this->success
            ? 'User profile suspended successfully.'
            : 'Profile does not exist';
        $this->displaySuspendPage($username);
    }
}

startSession('User Admin');
$page = new SuspendProfilePage();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $page->submitSuspend($_POST['username'] ?? '');
} else {
    $page->displaySuspendPage($_GET['username'] ?? '');
}
