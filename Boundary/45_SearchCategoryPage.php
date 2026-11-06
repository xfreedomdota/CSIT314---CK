<?php
require_once __DIR__ . '/layout.php';
require_once __DIR__ . '/../Control/45_SearchCategoryController.php';

class SearchCategoryPage
{
    private array $categories = [];
    private string $keyword = '';

    public function displaySearchPage(): void
    {
        renderHeader('IDP Categories');
        echo '<form method="get"><label>Search by name or description (leave empty to list all)</label>
                <input name="keyword" value="' . e($this->keyword) . '"><button type="submit">Search</button>
                <a class="btn light" href="41_CreateCategoryPage.php">+ New category</a></form>';

        if (count($this->categories) === 0) {
            echo '<br>';
            renderMessage('No matching categories found.', false);
        } else {
            echo '<table><tr><th>ID</th><th>Name</th><th>Description</th><th></th></tr>';
            foreach ($this->categories as $c) {
                $id = $c->getCategoryID();
                echo '<tr><td>' . $id . '</td><td>' . e($c->getCategoryName()) . '</td><td>' . e($c->getDescription()) . '</td>'
                   . '<td><a href="42_ViewCategoryPage.php?id=' . $id . '">View</a> &middot; <a href="43_UpdateCategoryPage.php?id=' . $id
                   . '">Update</a> &middot; <a href="44_DeleteCategoryPage.php?id=' . $id . '">Delete</a></td></tr>';
            }
            echo '</table>';
        }
        renderFooter();
    }

    public function submitSearch(string $keyword): void
    {
        $this->keyword    = $keyword;
        $this->categories = (new SearchCategoryController())->searchCategories($keyword);
        $this->displaySearchPage();
    }
}

startSession('Platform Manager');
(new SearchCategoryPage())->submitSearch($_GET['keyword'] ?? '');
