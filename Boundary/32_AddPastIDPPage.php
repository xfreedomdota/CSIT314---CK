<?php
require_once __DIR__ . '/layout.php';
require_once __DIR__ . '/../Control/32_AddPastIDPController.php';
require_once __DIR__ . '/../Control/45_SearchCategoryController.php';

class AddPastIDPPage
{
    private string $message = '';
    private bool $success = false;

    public function displayAddPage(): void
    {
        renderHeader('Add Past Project to Portfolio');
        renderMessage($this->message, $this->success);
        renderProjectForm(null, (new SearchCategoryController())->searchCategories(''), false, 'Add to portfolio');
        renderFooter();
    }

    public function addPastIDP(string $title, string $category, string $description, ?array $photos): void
    {
        $controller = new AddPastIDPController();
        $this->success = $controller->addPastIDP(currentUserId(), $title, $category, $description, $photos);
        $this->message = $this->success ? 'Past project added to your portfolio.' : $controller->getError();
        $this->displayAddPage();
    }
}

startSession('Interior Designer');
$page = new AddPastIDPPage();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $page->addPastIDP($_POST['title'] ?? '', $_POST['category'] ?? '', $_POST['description'] ?? '', uploadedPhoto());
} else {
    $page->displayAddPage();
}
