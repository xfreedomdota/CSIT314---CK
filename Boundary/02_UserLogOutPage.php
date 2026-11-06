<?php
require_once __DIR__ . '/layout.php';
require_once __DIR__ . '/../Control/02_UserLogOutController.php';

class UserLogOutPage
{
    public function displayPage(): void
    {
        renderHeader('Logout');
        echo '<p>Are you sure you want to end your administrator session?</p>
              <form method="post"><button type="submit" class="danger">Log out</button></form>';
        renderFooter();
    }

    public function clickLogout(): void
    {
        $controller = new UserLogOutController();
        if ($controller->logout()) {
            header('Location: 13_20_39_LoginPage.php');
            exit;
        }
    }
}

startSession('User Admin');
$page = new UserLogOutPage();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $page->clickLogout();
} else {
    $page->displayPage();
}
