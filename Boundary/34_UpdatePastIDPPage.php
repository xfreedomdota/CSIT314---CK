<?php
require_once __DIR__ . '/layout.php';
require_once __DIR__ . '/../Control/34_UpdatePastIDPController.php';
require_once __DIR__ . '/../Control/45_SearchCategoryController.php';

class UpdatePastIDPPage
{
    private string $message = '';
    private bool $success = false;

    public function displayUpdatePage(int $projectID): void
    {
        $idp = (new UpdatePastIDPController())->getOwnIDP($projectID, currentUserId(), 'Past');

        renderHeader('Update Past Project');
        renderMessage($this->message, $this->success);
        if ($idp === null) {
            renderMessage('Project not found.', false);
        } else {
            renderProjectForm($idp, (new SearchCategoryController())->searchCategories(''), false, 'Save changes');
        }
        echo '<p><a href="33_ViewPastIDPPage.php">&larr; Back to my portfolio</a></p>';
        renderFooter();
    }

    public function updatePastIDP(int $projectID, string $title, string $category, string $description, ?array $photos): void
    {
        $controller = new UpdatePastIDPController();
        $this->success = $controller->updatePastIDP($projectID, currentUserId(), $title, $category, $description, $photos);
        $this->message = $this->success ? 'Portfolio project updated.' : $controller->getError();
        $this->displayUpdatePage($projectID);
    }
}

startSession('Interior Designer');
$page = new UpdatePastIDPPage();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $page->updatePastIDP((int) ($_POST['projectID'] ?? 0), $_POST['title'] ?? '', $_POST['category'] ?? '',
                         $_POST['description'] ?? '', uploadedPhoto());
} else {
    $page->displayUpdatePage((int) ($_GET['id'] ?? 0));
}
