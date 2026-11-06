<?php
require_once __DIR__ . '/layout.php';
require_once __DIR__ . '/../Control/25_ShortlistSearchController.php';
require_once __DIR__ . '/../Control/45_SearchCategoryController.php';

class ShortlistSearchPage
{
    private array $projects = [];
    private string $keyword = '';
    private string $filter = '';

    public function displayShortlistPage(): void
    {
        $categories = (new SearchCategoryController())->searchCategories('');

        renderHeader('Search My Shortlist');
        echo '<form method="get"><div class="row">
                <div><label>Keyword</label><input name="keyword" value="' . e($this->keyword) . '"></div>
                <div><label>Category</label><select name="filter">' . renderCategoryOptions($categories, $this->filter) . '</select></div>
              </div><button type="submit">Search shortlist</button></form>';

        if (count($this->projects) === 0) {
            echo '<br>';
            renderMessage('No shortlisted projects match your search.', false);
        } else {
            renderProjectCards($this->projects, fn($p) => '<a href="23_ViewIDPPage.php?id=' . $p->getProjectID() . '">View project</a>');
        }
        renderFooter();
    }

    public function submitSearch(string $keyword, string $filter): void
    {
        $this->keyword  = $keyword;
        $this->filter   = $filter;
        $this->projects = (new ShortlistSearchController())->searchShortlist(currentUserId(), $keyword, $filter);
        $this->displayShortlistPage();
    }
}

startSession('Customer');
(new ShortlistSearchPage())->submitSearch($_GET['keyword'] ?? '', $_GET['filter'] ?? '');
