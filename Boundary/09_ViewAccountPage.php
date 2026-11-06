<?php
require_once __DIR__ . '/layout.php';
require_once __DIR__ . '/../Control/09_ViewAccountController.php';

class ViewAccountPage
{
    private ?UserAccount $account = null;
    private bool $searched = false;

    public function displayAccountPage(string $username): void
    {
        renderHeader('View User Account');
        echo '<form method="get">
                <label>Username</label><input name="username" value="' . e($username) . '" required>
                <button type="submit">View account</button>
              </form>';

        if ($this->account !== null) {
            echo '<table>
                    <tr><th>User ID</th><td>' . e((string) $this->account->getUserId()) . '</td></tr>
                    <tr><th>Username</th><td>' . e($this->account->getUsername()) . '</td></tr>
                    <tr><th>Email</th><td>' . e($this->account->getEmail()) . '</td></tr>
                    <tr><th>Status</th><td>' . statusPill($this->account->getStatus()) . '</td></tr>
                  </table>';
        } elseif ($this->searched) {
            echo '<br>';
            renderMessage('Account does not exist', false);
        }
        renderFooter();
    }

    public function submitViewRequest(string $username): void
    {
        $controller = new ViewAccountController();
        $this->account  = $controller->getAccount($username);
        $this->searched = true;
        $this->displayAccountPage($username);
    }
}

startSession('User Admin');
$page = new ViewAccountPage();
if (isset($_GET['username'])) {
    $page->submitViewRequest($_GET['username']);
} else {
    $page->displayAccountPage('');
}
