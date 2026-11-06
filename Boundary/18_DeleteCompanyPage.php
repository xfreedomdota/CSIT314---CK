<?php
require_once __DIR__ . '/layout.php';
require_once __DIR__ . '/../Control/18_DeleteCompanyController.php';

class DeleteCompanyPage
{
    private string $message = '';
    private bool $success = false;

    public function displayDeletePage(int $designerID): void
    {
        renderHeader('Delete Company Information');
        renderMessage($this->message, $this->success);
        if (!$this->success) {
            echo '<p>This removes your company information so customers no longer see it.</p>
                  <form method="post"><button type="submit" class="danger">Delete company information</button></form>';
        }
        renderFooter();
    }

    public function submitDelete(int $designerID): void
    {
        $this->success = (new DeleteCompanyController())->deleteCompany($designerID);
        $this->message = $this->success ? 'Company information deleted.' : 'There is no company information to delete.';
        $this->displayDeletePage($designerID);
    }
}

startSession('Interior Designer');
$page = new DeleteCompanyPage();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $page->submitDelete(currentUserId());
} else {
    $page->displayDeletePage(currentUserId());
}
