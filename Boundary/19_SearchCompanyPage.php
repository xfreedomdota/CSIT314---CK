<?php
require_once __DIR__ . '/layout.php';
require_once __DIR__ . '/../Control/19_SearchCompanyController.php';

class SearchCompanyPage
{
    private ?array $companies = null;
    private string $keyword = '';

    public function displaySearchPage(): void
    {
        renderHeader('Search Companies');
        echo '<form method="get"><label>Company name, description or address</label>
                <input name="keyword" value="' . e($this->keyword) . '"><button type="submit">Search</button></form>';

        if ($this->companies !== null && count($this->companies) === 0) {
            echo '<br>';
            renderMessage('No matching companies found.', false);
        } elseif ($this->companies !== null) {
            echo '<table><tr><th>Company</th><th>Description</th><th>Address</th><th>Designer</th></tr>';
            foreach ($this->companies as $c) {
                echo '<tr><td><strong>' . e($c->getCompanyName()) . '</strong><br><span class="muted">Reg: ' . e($c->getRegNum())
                   . '</span></td><td>' . e($c->getDescription()) . '</td><td>' . e($c->getAddress()) . '</td><td>'
                   . e($c->getDesignerName()) . '</td></tr>';
            }
            echo '</table>';
        }
        renderFooter();
    }

    public function submitSearch(string $keyword): void
    {
        $this->keyword   = $keyword;
        $this->companies = (new SearchCompanyController())->searchCompanies($keyword);
        $this->displaySearchPage();
    }
}

startSession('Interior Designer');
$page = new SearchCompanyPage();
$page->submitSearch($_GET['keyword'] ?? '');
