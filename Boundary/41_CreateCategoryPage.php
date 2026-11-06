<?php
require_once __DIR__ . '/layout.php';
require_once __DIR__ . '/../Control/41_CreateCategoryController.php';

class CreateCategoryPage
{
    private string $message = '';
    private bool $success = false;

    public function displayCreateCategoryPage(): void
    {
        renderHeader('Create IDP Category');
        renderMessage($this->message, $this->success);
        echo '<form method="post">
                <label>Category name</label><input name="categoryName" required>
                <label>Description</label><textarea name="description" rows="3"></textarea>
                <button type="submit">Create category</button>
              </form>';
        renderFooter();
    }

    public function createCategory(string $categoryName, string $description): void
    {
        $controller = new CreateCategoryController();
        $this->success = $controller->createCategory($categoryName, $description);

        if ($this->success) {
            $this->message = 'Category created.';
        } elseif ($controller->getError() === 'exists') {
            $this->message = 'A category with this name already exists.';
        } else {
            $this->message = 'Category name is required.';
        }
        $this->displayCreateCategoryPage();
    }
}

startSession('Platform Manager');
$page = new CreateCategoryPage();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $page->createCategory($_POST['categoryName'] ?? '', $_POST['description'] ?? '');
} else {
    $page->displayCreateCategoryPage();
}
