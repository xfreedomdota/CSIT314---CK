<?php
require_once __DIR__ . '/layout.php';
require_once __DIR__ . '/../Control/15_RegisterCompanyController.php';

class RegisterCompanyPage
{
    private string $message = '';
    private bool $success = false;

    public function displayRegisterPage(): void
    {
        renderHeader('Register Company Information');
        renderMessage($this->message, $this->success);
        echo '<form method="post">
                <label>Company name</label><input name="companyName" required>
                <label>Registration number</label><input name="regNum">
                <label>Description</label><textarea name="description" rows="4"></textarea>
                <label>Address</label><input name="address">
                <button type="submit">Register company</button>
              </form>';
        renderFooter();
    }

    public function submitRegistration(string $companyName, string $regNum, string $description, string $address): void
    {
        $controller = new RegisterCompanyController();
        $this->success = $controller->registerCompany(currentUserId(), $companyName, $regNum, $description, $address);

        if ($this->success) {
            $this->message = 'Company information registered. Customers can now see it.';
        } elseif ($controller->getError() === 'exists') {
            $this->message = 'You have already registered a company. Use Update Company instead.';
        } else {
            $this->message = 'Company name is required.';
        }
        $this->displayRegisterPage();
    }
}

startSession('Interior Designer');
$page = new RegisterCompanyPage();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $page->submitRegistration($_POST['companyName'] ?? '', $_POST['regNum'] ?? '', $_POST['description'] ?? '', $_POST['address'] ?? '');
} else {
    $page->displayRegisterPage();
}
