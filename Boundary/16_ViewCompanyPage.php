<?php
require_once __DIR__ . '/layout.php';
require_once __DIR__ . '/../Control/16_ViewCompanyController.php';

class ViewCompanyPage
{
    private ?Company $company = null;

    public function displayCompanyPage(): void
    {
        $company = $this->company;

        renderHeader('My Company Information');
        if ($company === null) {
            renderMessage('You have not registered any company information yet.', false);
            echo '<a class="btn" href="15_RegisterCompanyPage.php">Register company</a>';
        } else {
            echo '<p class="muted">This is what customers see:</p><table>
                    <tr><th>Company name</th><td>' . e($company->getCompanyName()) . '</td></tr>
                    <tr><th>Registration number</th><td>' . e($company->getRegNum()) . '</td></tr>
                    <tr><th>Description</th><td>' . nl2br(e($company->getDescription())) . '</td></tr>
                    <tr><th>Address</th><td>' . e($company->getAddress()) . '</td></tr>
                    <tr><th>Designer</th><td>' . e($company->getDesignerName()) . '</td></tr>
                  </table>
                  <a class="btn" href="17_UpdateCompanyPage.php">Update</a>
                  <a class="btn danger" href="18_DeleteCompanyPage.php">Delete</a>';
        }
        renderFooter();
    }

    public function requestCompanyInfo(int $designerID): void
    {
        $this->company = (new ViewCompanyController())->getCompany($designerID);
        $this->displayCompanyPage();
    }
}

startSession('Interior Designer');
(new ViewCompanyPage())->requestCompanyInfo(currentUserId());
