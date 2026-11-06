<?php
require_once __DIR__ . '/layout.php';
require_once __DIR__ . '/../Control/22_SearchIDPController.php';
require_once __DIR__ . '/../Control/45_SearchCategoryController.php';

class SearchIDPPage
{
    private array $projects = [];
    private string $keyword = '';
    private string $category = '';

    public function displaySearchPage(): void
    {
        $categories = (new SearchCategoryController())->searchCategories('');

        renderHeader('Search Interior Design Projects');
        echo '<form method="get"><div class="row">
                <div><label>Keyword</label><input name="keyword" value="' . e($this->keyword) . '"></div>
                <div><label>Category</label><select name="category">' . renderCategoryOptions($categories, $this->category) . '</select></div>
              </div><button type="submit">Search</button></form>';

        if (count($this->projects) === 0) {
            echo '<br>';
            renderMessage('No matching projects found.', false);
        } else {
            renderProjectCards($this->projects, fn($p) => '<a class="btn" href="23_ViewIDPPage.php?id=' . $p->getProjectID() . '">View project</a>');
        }
        renderFooter();
    }

    public function submitSearch(string $keyword, string $category): void
    {
        $this->keyword  = $keyword;
        $this->category = $category;
        $this->projects = (new SearchIDPController())->searchIDP($keyword, $category);
        $this->displaySearchPage();
    }
}

startSession('Customer');
(new SearchIDPPage())->submitSearch($_GET['keyword'] ?? '', $_GET['category'] ?? '');
