<?php
require_once __DIR__ . '/layout.php';
require_once __DIR__ . '/../Control/43_UpdateCategoryController.php';
require_once __DIR__ . '/../Control/42_ViewCategoryController.php';

class UpdateCategoryPage
{
    private string $message = '';
    private bool $success = false;

    public function displayUpdatePage(int $categoryID): void
    {
        $category = (new ViewCategoryController())->getCategoryDetails($categoryID);

        renderHeader('Update IDP Category');
        renderMessage($this->message, $this->success);
        if ($category === null) {
            renderMessage('Category does not exist.', false);
        } else {
            echo '<form method="post"><input type="hidden" name="categoryID" value="' . $categoryID . '">
                    <label>Category name</label><input name="categoryName" required value="' . e($category->getCategoryName()) . '">
                    <label>Description</label><textarea name="description" rows="3">' . e($category->getDescription()) . '</textarea>
                    <button type="submit">Save changes</button></form>';
        }
        echo '<p><a href="45_SearchCategoryPage.php">&larr; All categories</a></p>';
        renderFooter();
    }

    public function submitUpdate(int $categoryID, string $categoryName, string $description): void
    {
        $this->success = (new UpdateCategoryController())->updateCategory($categoryID, $categoryName, $description);
        $this->message = $this->success ? 'Category updated.' : 'Name is required and must not match another category.';
        $this->displayUpdatePage($categoryID);
    }
}

startSession('Platform Manager');
$page = new UpdateCategoryPage();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $page->submitUpdate((int) ($_POST['categoryID'] ?? 0), $_POST['categoryName'] ?? '', $_POST['description'] ?? '');
} else {
    $page->displayUpdatePage((int) ($_GET['id'] ?? 0));
}
