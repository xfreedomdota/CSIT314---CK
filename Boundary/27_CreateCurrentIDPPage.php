<?php
require_once __DIR__ . '/layout.php';
require_once __DIR__ . '/../Control/27_CreateCurrentIDPController.php';
require_once __DIR__ . '/../Control/45_SearchCategoryController.php';

class CreateCurrentIDPPage
{
    private string $message = '';
    private bool $success = false;

    public function displayCreatePage(): void
    {
        renderHeader('Create Current Project');
        renderMessage($this->message, $this->success);
        renderProjectForm(null, (new SearchCategoryController())->searchCategories(''), true, 'Create project');
        renderFooter();
    }

    public function createIDP(string $title, string $category, string $description, string $status, int $progress, ?array $photos): void
    {
        $controller = new CreateCurrentIDPController();
        $this->success = $controller->createIDP(currentUserId(), $title, $category, $description, $status, $progress, $photos);
        $this->message = $this->success ? 'Current project created and now visible to customers.' : $controller->getError();
        $this->displayCreatePage();
    }
}

startSession('Interior Designer');
$page = new CreateCurrentIDPPage();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $page->createIDP($_POST['title'] ?? '', $_POST['category'] ?? '', $_POST['description'] ?? '',
                     $_POST['status'] ?? '', (int) ($_POST['progress'] ?? 0), uploadedPhoto());
} else {
    $page->displayCreatePage();
}
