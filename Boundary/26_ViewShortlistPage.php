<?php
require_once __DIR__ . '/layout.php';
require_once __DIR__ . '/../Control/26_ViewShortlistController.php';

class ViewShortlistPage
{
    private array $projects = [];

    public function displayShortlistedIDP(): void
    {
        renderHeader('My Shortlist');
        if (count($this->projects) === 0) {
            renderMessage('Your shortlist is empty. Save projects you like to compare them here.', false);
            echo '<a class="btn" href="22_SearchIDPPage.php">Browse projects</a>';
        } else {
            echo '<div style="overflow-x:auto"><table><tr><th></th>';
            foreach ($this->projects as $p) {
                echo '<th style="min-width:200px"><div class="project">' . photoTag($p->getPhoto(), $p->getTitle()) . '</div>'
                   . '<a href="23_ViewIDPPage.php?id=' . $p->getProjectID() . '">' . e($p->getTitle()) . '</a></th>';
            }
            echo '</tr>';
            $rows = [
                'Category' => fn($p) => e($p->getCategory()),
                'Status'   => fn($p) => statusPill($p->getStatus()),
                'Progress' => fn($p) => $p->getProgress() . '%',
                'Designer' => fn($p) => e($p->getDesignerName()),
                'Company'  => fn($p) => e($p->getCompanyName() ?: '-'),
                'About'    => fn($p) => e($p->getDescription()),
            ];
            foreach ($rows as $label => $value) {
                echo '<tr><th>' . $label . '</th>';
                foreach ($this->projects as $p) {
                    echo '<td>' . $value($p) . '</td>';
                }
                echo '</tr>';
            }
            echo '</table></div>';
        }
        renderFooter();
    }

    public function requestShortlistedIDP(int $customerId): void
    {
        $this->projects = (new ViewShortlistController())->getShortlistedIDP($customerId);
        $this->displayShortlistedIDP();
    }
}

startSession('Customer');
(new ViewShortlistPage())->requestShortlistedIDP(currentUserId());
