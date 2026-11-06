<?php
require_once __DIR__ . '/layout.php';
require_once __DIR__ . '/../Control/17_UpdateCompanyController.php';
require_once __DIR__ . '/../Control/16_ViewCompanyController.php';

class UpdateCompanyPage
{
    private string $message = '';
    private bool $success = false;

    public function displayUpdatePage(int $designerID): void
    {
        $company = (new ViewCompanyController())->getCompany($designerID);

        renderHeader('Update Company Information');
        renderMessage($this->message, $this->success);
        if ($company === null) {
            renderMessage('No company registered yet.', false);
            echo '<a class="btn" href="15_RegisterCompanyPage.php">Register company</a>';
        } else {
            echo '<form method="post">
                    <label>Company name</label><input name="companyName" required value="' . e($company->getCompanyName()) . '">
                    <label>Registration number</label><input name="regNum" value="' . e($company->getRegNum()) . '">
                    <label>Description</label><textarea name="description" rows="4">' . e($company->getDescription()) . '</textarea>
                    <label>Address</label><input name="address" value="' . e($company->getAddress()) . '">
                    <button type="submit">Save changes</button>
                  </form>';
        }
        renderFooter();
    }

    public function submitUpdate(int $designerID, string $companyName, string $regNum, string $description, string $address): void
    {
        $this->success = (new UpdateCompanyController())->updateCompany($designerID, $companyName, $regNum, $description, $address);
        $this->message = $this->success ? 'Company information updated.' : 'Company name is required.';
        $this->displayUpdatePage($designerID);
    }
}

startSession('Interior Designer');
$page = new UpdateCompanyPage();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $page->submitUpdate(currentUserId(), $_POST['companyName'] ?? '', $_POST['regNum'] ?? '', $_POST['description'] ?? '', $_POST['address'] ?? '');
} else {
    $page->displayUpdatePage(currentUserId());
}
