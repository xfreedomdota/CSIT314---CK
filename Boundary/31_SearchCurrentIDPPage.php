<?php
require_once __DIR__ . '/layout.php';
require_once __DIR__ . '/../Control/31_SearchCurrentIDPController.php';

class SearchCurrentIDPPage
{
    private array $projects = [];
    private string $keyword = '';
    private string $status = '';

    public function displaySearchPage(): void
    {
        renderHeader('Search My Current Projects');
        echo '<form method="get"><div class="row">
                <div><label>Keyword</label><input name="keyword" value="' . e($this->keyword) . '"></div>
                <div><label>Status</label><select name="status"><option value="">All statuses</option>';
        foreach (IDP::STATUSES as $s) {
            echo '<option' . ($s === $this->status ? ' selected' : '') . '>' . e($s) . '</option>';
        }
        echo '</select></div></div><button type="submit">Search</button></form>';

        if (count($this->projects) === 0) {
            echo '<br>';
            renderMessage('No matching current projects found.', false);
        } else {
            renderProjectCards($this->projects, fn($p) =>
                '<a href="29_UpdateCurrentIDPPage.php?id=' . $p->getProjectID() . '">Update</a> &middot; '
              . '<a href="30_DeleteCurrentIDPPage.php?id=' . $p->getProjectID() . '">Delete</a>');
        }
        renderFooter();
    }

    public function submitSearch(int $designerID, string $keyword, string $status): void
    {
        $this->keyword  = $keyword;
        $this->status   = $status;
        $this->projects = (new SearchCurrentIDPController())->searchIDP($designerID, $keyword, $status);
        $this->displaySearchPage();
    }
}

startSession('Interior Designer');
(new SearchCurrentIDPPage())->submitSearch(currentUserId(), $_GET['keyword'] ?? '', $_GET['status'] ?? '');
