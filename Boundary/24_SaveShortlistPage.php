<?php
require_once __DIR__ . '/layout.php';
require_once __DIR__ . '/../Control/24_SaveShortlistController.php';

class SaveShortlistPage
{
    private string $message = 'Open a project and click "Save to my shortlist".';
    private bool $success = false;

    public function displaySaveButton(): void
    {
        renderHeader('Save to Shortlist');
        renderMessage($this->message, $this->success);
        echo '<a class="btn" href="26_ViewShortlistPage.php">View my shortlist</a>
              <a class="btn light" href="22_SearchIDPPage.php">Keep browsing</a>';
        renderFooter();
    }

    public function saveIDPToShortlist(int $customerId, int $projectId): void
    {
        $controller = new SaveShortlistController();
        $this->success = $controller->saveIDPToShortlist($customerId, $projectId);

        if ($this->success) {
            $this->message = 'Project saved to your shortlist.';
        } elseif ($controller->getError() === 'exists') {
            $this->message = 'This project is already in your shortlist.';
        } else {
            $this->message = 'Project does not exist.';
        }
        $this->displaySaveButton();
    }
}

startSession('Customer');
$page = new SaveShortlistPage();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $page->saveIDPToShortlist(currentUserId(), (int) ($_POST['projectID'] ?? 0));
} else {
    $page->displaySaveButton();
}
