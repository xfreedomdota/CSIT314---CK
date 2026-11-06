<?php
require_once __DIR__ . '/layout.php';
require_once __DIR__ . '/../Control/03_CreateProfileController.php';

class CreateProfilePage
{
    private string $message = '';
    private bool $success = false;
    private string $username = '';

    public function displayPage(): void
    {
        renderHeader('Create User Profile');
        renderMessage($this->message, $this->success);
        if ($this->success) {
            echo '<p><a class="btn light" href="08_CreateAccountPage.php?username=' . urlencode($this->username) . '">Next: create the login account for ' . e($this->username) . '</a></p>';
        }
        echo '<form method="post">
                <label>Username</label><input name="username" required value="' . e($_GET['username'] ?? '') . '">
                <label>Password</label><input name="password" type="password" required>
                <label>Name</label><input name="name" required>
                <label>User type</label><select name="userType">';
        foreach (UserProfile::USER_TYPES as $type) {
            echo '<option>' . e($type) . '</option>';
        }
        echo '</select><button type="submit">Create profile</button></form>';
        renderFooter();
    }

    public function submitProfile(string $username, string $password, string $name, string $userType): void
    {
        $this->username = $username;
        $controller = new CreateProfileController();
        $this->success = $controller->createProfile($username, $password, $name, $userType);
        $this->message = $this->success
            ? 'User profile created successfully.'
            : 'Invalid or incomplete data, or the username already has a profile.';
        $this->displayPage();
    }
}

startSession('User Admin');
$page = new CreateProfilePage();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $page->submitProfile($_POST['username'] ?? '', $_POST['password'] ?? '', $_POST['name'] ?? '', $_POST['userType'] ?? '');
} else {
    $page->displayPage();
}
