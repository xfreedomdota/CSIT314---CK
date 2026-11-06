<?php
require_once __DIR__ . '/layout.php';
require_once __DIR__ . '/../Control/14_21_40_LogOutController.php';

class LogOutPage
{
    public function displayPage(): void
    {
        renderHeader('Logout');
        echo '<p>Log out so nobody else on this device can access your account?</p>
              <form method="post"><button type="submit" class="danger">Log out</button></form>';
        renderFooter();
    }

    public function clickLogout(): void
    {
        if ((new LogOutController())->logout()) {
            $this->redirectToLogin();
        }
    }

    public function redirectToLogin(): void
    {
        header('Location: 13_20_39_LoginPage.php');
        exit;
    }
}

startSession();
$page = new LogOutPage();
if (empty($_SESSION['role'])) {
    $page->redirectToLogin();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $page->clickLogout();
} else {
    $page->displayPage();
}
