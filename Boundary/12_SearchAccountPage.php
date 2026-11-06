<?php
require_once __DIR__ . '/layout.php';
require_once __DIR__ . '/../Control/12_SearchAccountController.php';

class SearchAccountPage
{
    private array|false|null $accounts = null;
    private string $keyword = '';

    public function displaySearchPage(): void
    {
        renderHeader('Search User Accounts');
        echo '<form method="get">
                <label>User ID or keyword (username / email)</label>
                <input name="keyword" value="' . e($this->keyword) . '">
                <button type="submit">Search</button>
              </form><br>';

        if ($this->accounts === false) {
            renderMessage('Please enter a valid ID or keyword.', false);
        } elseif (is_array($this->accounts) && count($this->accounts) === 0) {
            renderMessage('No matching accounts found', false);
        } elseif (is_array($this->accounts)) {
            echo '<table><tr><th>ID</th><th>Username</th><th>Email</th><th>Status</th></tr>';
            foreach ($this->accounts as $a) {
                echo '<tr><td>' . e((string) $a->getUserId()) . '</td><td>' . e($a->getUsername()) . '</td><td>'
                    . e($a->getEmail()) . '</td><td>' . statusPill($a->getStatus()) . '</td></tr>';
            }
            echo '</table>';
        }
        renderFooter();
    }

    public function submitSearch(string $keyword): void
    {
        $controller = new SearchAccountController();
        $this->keyword  = $keyword;
        $this->accounts = $controller->searchAccounts($keyword);
        $this->displaySearchPage();
    }
}

startSession('User Admin');
$page = new SearchAccountPage();
if (isset($_GET['keyword'])) {
    $page->submitSearch($_GET['keyword']);
} else {
    $page->displaySearchPage();
}
