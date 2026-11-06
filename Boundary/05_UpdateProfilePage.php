<?php
require_once __DIR__ . '/layout.php';
require_once __DIR__ . '/../Control/05_UpdateProfileController.php';
require_once __DIR__ . '/../Control/04_ViewProfileController.php';

class UpdateProfilePage
{
    private string $message = '';
    private bool $success = false;

    public function displayUpdatePage(string $username): void
    {
        renderHeader('Update User Profile');
        renderMessage($this->message, $this->success);
        echo '<form method="get">
                <label>Username</label><input name="username" value="' . e($username) . '" required>
                <button type="submit">Load profile</button>
              </form>';

        if ($username === '') {
            renderFooter();
            return;
        }
        $profile = (new ViewProfileController())->getProfile($username);
        if ($profile === null) {
            echo '<br>';
            renderMessage('Profile does not exist', false);
            renderFooter();
            return;
        }

        echo '<hr><form method="post">
                <input type="hidden" name="username" value="' . e($profile->getUsername()) . '">
                <label>New password (leave blank to keep current)</label><input name="password" type="password">
                <label>Name</label><input name="name" value="' . e($profile->getName()) . '" required>
                <label>User type</label><select name="userType">';
        foreach (UserProfile::USER_TYPES as $type) {
            $selected = $type === $profile->getUserType() ? ' selected' : '';
            echo '<option' . $selected . '>' . e($type) . '</option>';
        }
        echo '</select><button type="submit">Save changes</button></form>';
        renderFooter();
    }

    public function submitUpdate(string $username, string $password, string $name, string $userType): void
    {
        $controller = new UpdateProfileController();
        $this->success = $controller->updateProfile($username, $password, $name, $userType);
        $this->message = $this->success
            ? 'User profile updated successfully.'
            : 'Invalid or incomplete data. Please correct it.';
        $this->displayUpdatePage($username);
    }
}

startSession('User Admin');
$page = new UpdateProfilePage();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $page->submitUpdate($_POST['username'] ?? '', $_POST['password'] ?? '', $_POST['name'] ?? '', $_POST['userType'] ?? '');
} else {
    $page->displayUpdatePage($_GET['username'] ?? '');
}
