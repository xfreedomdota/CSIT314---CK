<?php
require_once __DIR__ . '/layout.php';
require_once __DIR__ . '/../Control/36_SearchPastIDPController.php';
require_once __DIR__ . '/../Control/45_SearchCategoryController.php';

class SearchPastIDPPage
{
    private array $projects = [];
    private string $keyword = '';
    private string $style = '';

    public function displaySearchPage(): void
    {
        $categories = (new SearchCategoryController())->searchCategories('');

        renderHeader('Search My Past Portfolio');
        echo '<form method="get"><div class="row">
                <div><label>Keyword</label><input name="keyword" value="' . e($this->keyword) . '"></div>
                <div><label>Style</label><select name="style">' . renderCategoryOptions($categories, $this->style) . '</select></div>
              </div><button type="submit">Search</button></form>';

        if (count($this->projects) === 0) {
            echo '<br>';
            renderMessage('No matching past projects found.', false);
        } else {
            renderProjectCards($this->projects, fn($p) =>
                '<a href="34_UpdatePastIDPPage.php?id=' . $p->getProjectID() . '">Update</a> &middot; '
              . '<a href="35_DeletePastIDPPage.php?id=' . $p->getProjectID() . '">Delete</a>');
        }
        renderFooter();
    }

    public function submitSearch(int $designerID, string $keyword, string $style): void
    {
        $this->keyword  = $keyword;
        $this->style    = $style;
        $this->projects = (new SearchPastIDPController())->searchPastIDPs($designerID, $keyword, $style);
        $this->displaySearchPage();
    }
}

startSession('Interior Designer');
(new SearchPastIDPPage())->submitSearch(currentUserId(), $_GET['keyword'] ?? '', $_GET['style'] ?? '');
