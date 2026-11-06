<?php
require_once __DIR__ . '/layout.php';
require_once __DIR__ . '/../Control/07_SearchProfileController.php';

class SearchProfilePage
{
    private ?array $profiles = null;
    private string $filter = '';

    public function displaySearchPage(): void
    {
        renderHeader('Search User Profiles');
        echo '<form method="get">
                <label>Filter (username, name or user type)</label>
                <input name="filter" value="' . e($this->filter) . '">
                <button type="submit">Search</button>
              </form>';

        if ($this->profiles !== null && count($this->profiles) > 0) {
            echo '<table><tr><th>Username</th><th>Name</th><th>User type</th><th>Status</th></tr>';
            foreach ($this->profiles as $p) {
                echo '<tr><td>' . e($p->getUsername()) . '</td><td>' . e($p->getName()) . '</td><td>'
                    . e($p->getUserType()) . '</td><td>' . statusPill($p->getStatus()) . '</td></tr>';
            }
            echo '</table>';
        } elseif ($this->profiles !== null) {
            echo '<br>';
            renderMessage('Profile does not exist', false);
        }
        renderFooter();
    }

    public function submitSearch(string $filter): void
    {
        $controller = new SearchProfileController();
        $this->filter   = $filter;
        $this->profiles = $controller->searchProfiles($filter);
        $this->displaySearchPage();
    }
}

startSession('User Admin');
$page = new SearchProfilePage();
if (isset($_GET['filter'])) {
    $page->submitSearch($_GET['filter']);
} else {
    $page->displaySearchPage();
}
