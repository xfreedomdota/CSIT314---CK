<?php
require_once __DIR__ . '/layout.php';
require_once __DIR__ . '/../Control/04_ViewProfileController.php';

class ViewProfilePage
{
    private ?UserProfile $profile = null;
    private bool $searched = false;

    public function displayProfile(string $username): void
    {
        renderHeader('View User Profile');
        echo '<form method="get">
                <label>Username</label><input name="username" value="' . e($username) . '" required>
                <button type="submit">View profile</button>
              </form>';

        if ($this->profile !== null) {
            echo '<table>
                    <tr><th>Username</th><td>' . e($this->profile->getUsername()) . '</td></tr>
                    <tr><th>Name</th><td>' . e($this->profile->getName()) . '</td></tr>
                    <tr><th>User type</th><td>' . e($this->profile->getUserType()) . '</td></tr>
                    <tr><th>Status</th><td>' . statusPill($this->profile->getStatus()) . '</td></tr>
                  </table>';
        } elseif ($this->searched) {
            echo '<br>';
            renderMessage('Profile does not exist', false);
        }
        renderFooter();
    }

    public function requestProfile(string $username): void
    {
        $controller = new ViewProfileController();
        $this->profile  = $controller->getProfile($username);
        $this->searched = true;
        $this->displayProfile($username);
    }
}

startSession('User Admin');
$page = new ViewProfilePage();
if (isset($_GET['username'])) {
    $page->requestProfile($_GET['username']);
} else {
    $page->displayProfile('');
}
