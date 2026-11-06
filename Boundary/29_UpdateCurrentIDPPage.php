<?php
require_once __DIR__ . '/layout.php';
require_once __DIR__ . '/../Control/29_UpdateCurrentIDPController.php';
require_once __DIR__ . '/../Control/45_SearchCategoryController.php';

class UpdateCurrentIDPPage
{
    private string $message = '';
    private bool $success = false;

    public function displayUpdatePage(int $projectID): void
    {
        $idp = (new UpdateCurrentIDPController())->getOwnIDP($projectID, currentUserId(), 'Current');

        renderHeader('Update Current Project');
        renderMessage($this->message, $this->success);
        if ($idp === null) {
            renderMessage('Project not found.', false);
        } else {
            renderProjectForm($idp, (new SearchCategoryController())->searchCategories(''), true, 'Save changes');
        }
        echo '<p><a href="28_ViewCurrentIDPPage.php">&larr; Back to my current projects</a></p>';
        renderFooter();
    }

    public function UpdateIDP(int $projectID, string $title, string $category, string $description,
                              string $status, int $progress, ?array $photos): void
    {
        $controller = new UpdateCurrentIDPController();
        $this->success = $controller->UpdateIDP($projectID, currentUserId(), $title, $category, $description, $status, $progress, $photos);
        $this->message = $this->success ? 'Project updated. Clients now see the latest progress.' : $controller->getError();
        $this->displayUpdatePage($projectID);
    }
}

startSession('Interior Designer');
$page = new UpdateCurrentIDPPage();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $page->UpdateIDP((int) ($_POST['projectID'] ?? 0), $_POST['title'] ?? '', $_POST['category'] ?? '',
                     $_POST['description'] ?? '', $_POST['status'] ?? '', (int) ($_POST['progress'] ?? 0), uploadedPhoto());
} else {
    $page->displayUpdatePage((int) ($_GET['id'] ?? 0));
}
