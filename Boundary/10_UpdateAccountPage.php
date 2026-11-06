<?php
require_once __DIR__ . '/layout.php';
require_once __DIR__ . '/../Control/10_UpdateAccountController.php';
require_once __DIR__ . '/../Control/09_ViewAccountController.php';

class UpdateAccountPage
{
    private string $message = '';
    private bool $success = false;

    public function displayUpdatePage(string $username): void
    {
        renderHeader('Update User Account');
        renderMessage($this->message, $this->success);
        echo '<form method="get">
                <label>Username</label><input name="username" value="' . e($username) . '" required>
                <button type="submit">Load account</button>
              </form>';

        if ($username === '') {
            renderFooter();
            return;
        }
        $account = (new ViewAccountController())->getAccount($username);
        if ($account === null) {
            echo '<br>';
            renderMessage('Account does not exist', false);
            renderFooter();
            return;
        }

        echo '<hr><form method="post">
                <input type="hidden" name="username" value="' . e($account->getUsername()) . '">
                <label>New password (leave blank to keep current)</label><input name="password" type="password">
                <label>Email</label><input name="email" type="email" value="' . e($account->getEmail()) . '" required>
                <label>Status</label><select name="status">';
        foreach (UpdateAccountController::STATUSES as $status) {
            $selected = $status === $account->getStatus() ? ' selected' : '';
            echo '<option' . $selected . '>' . e($status) . '</option>';
        }
        echo '</select><button type="submit">Save changes</button></form>';
        renderFooter();
    }

    public function submitUpdate(string $username, string $password, string $email, string $status): void
    {
        $controller = new UpdateAccountController();
        $this->success = $controller->updateAccount($username, $password, $email, $status);
        $this->message = $this->success
            ? 'User account updated successfully.'
            : 'Invalid or missing details. Please correct them.';
        $this->displayUpdatePage($username);
    }
}

startSession('User Admin');
$page = new UpdateAccountPage();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $page->submitUpdate($_POST['username'] ?? '', $_POST['password'] ?? '', $_POST['email'] ?? '', $_POST['status'] ?? '');
} else {
    $page->displayUpdatePage($_GET['username'] ?? '');
}
