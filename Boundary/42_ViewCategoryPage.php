<?php
require_once __DIR__ . '/layout.php';
require_once __DIR__ . '/../Control/42_ViewCategoryController.php';

class ViewCategoryPage
{
    public function displayCategoryDetails(int $categoryID): void
    {
        $controller = new ViewCategoryController();
        $category   = $controller->getCategoryDetails($categoryID);

        renderHeader('View IDP Category');
        if ($category === null) {
            renderMessage('Category does not exist.', false);
        } else {
            echo '<table>
                    <tr><th>ID</th><td>' . $category->getCategoryID() . '</td></tr>
                    <tr><th>Name</th><td>' . e($category->getCategoryName()) . '</td></tr>
                    <tr><th>Description</th><td>' . e($category->getDescription()) . '</td></tr>
                    <tr><th>Projects using it</th><td>' . $controller->countProjects($category) . '</td></tr>
                  </table>
                  <a class="btn" href="43_UpdateCategoryPage.php?id=' . $categoryID . '">Update</a>
                  <a class="btn danger" href="44_DeleteCategoryPage.php?id=' . $categoryID . '">Delete</a>';
        }
        echo '<p><a href="45_SearchCategoryPage.php">&larr; All categories</a></p>';
        renderFooter();
    }
}

startSession('Platform Manager');
(new ViewCategoryPage())->displayCategoryDetails((int) ($_GET['id'] ?? 0));
