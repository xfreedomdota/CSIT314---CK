<?php
require_once __DIR__ . '/layout.php';
require_once __DIR__ . '/../Control/38_IDPAnalyticsController.php';

class IDPAnalyticsPage
{
    public function displayShortlistStats(int $designerID): void
    {
        $projects = (new IDPAnalyticsController())->getShortlistCount($designerID);

        renderHeader('Times Shortlisted');
        if (count($projects) === 0) {
            renderMessage('You have no projects yet.', false);
        } else {
            $max = max(1, max(array_map(fn($p) => $p->getShortlistCount(), $projects)));
            echo '<p class="muted">Most shortlisted first - a sign of real customer interest in each design style.</p>
                  <table><tr><th>Project</th><th>Style</th><th style="width:40%">Shortlisted</th></tr>';
            foreach ($projects as $p) {
                echo '<tr><td>' . e($p->getTitle()) . '</td><td>' . e($p->getCategory()) . '</td>'
                   . '<td><strong>' . $p->getShortlistCount() . '</strong><div class="bar"><span style="width:'
                   . round($p->getShortlistCount() / $max * 100) . '%"></span></div></td></tr>';
            }
            echo '</table>';
        }
        renderFooter();
    }
}

startSession('Interior Designer');
(new IDPAnalyticsPage())->displayShortlistStats(currentUserId());
