<?php
require_once __DIR__ . '/layout.php';
require_once __DIR__ . '/../Control/44_DeleteCategoryController.php';
require_once __DIR__ . '/../Control/42_ViewCategoryController.php';

class DeleteCategoryPage
{
    private string $message = '';
    private bool $success = false;

    public function displayDeletePage(int $categoryID): void
    {
        renderHeader('Delete IDP Category');
        renderMessage($this->message, $this->success);
        if (!$this->success) {
            $category = (new ViewCategoryController())->getCategoryDetails($categoryID);
            if ($category === null) {
                renderMessage('Category does not exist.', false);
            } else {
                echo '<p>Delete category <strong>' . e($category->getCategoryName()) . '</strong>?
                      Only categories no project uses can be deleted.</p>
                      <form method="post"><input type="hidden" name="categoryID" value="' . $categoryID . '">
                      <button type="submit" class="danger">Delete category</button></form>';
            }
        }
        echo '<p><a href="45_SearchCategoryPage.php">&larr; All categories</a></p>';
        renderFooter();
    }

    public function submitDelete(int $categoryID): void
    {
        $controller = new DeleteCategoryController();
        $this->success = $controller->deleteCategory($categoryID);

        if ($this->success) {
            $this->message = 'Category deleted.';
        } elseif ($controller->getError() === 'in_use') {
            $this->message = 'This category is still used by projects, so it cannot be deleted.';
        } else {
            $this->message = 'Category does not exist.';
        }
        $this->displayDeletePage($categoryID);
    }
}

startSession('Platform Manager');
$page = new DeleteCategoryPage();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $page->submitDelete((int) ($_POST['categoryID'] ?? 0));
} else {
    $page->displayDeletePage((int) ($_GET['id'] ?? 0));
}
