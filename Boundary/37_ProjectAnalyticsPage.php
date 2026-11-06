<?php
require_once __DIR__ . '/layout.php';
require_once __DIR__ . '/../Control/37_ProjectAnalyticsController.php';

class ProjectAnalyticsPage
{
    public function displayProjectStats(int $designerID): void
    {
        $projects = (new ProjectAnalyticsController())->getIDPViewCount($designerID);

        renderHeader('Project Views');
        if (count($projects) === 0) {
            renderMessage('You have no projects yet.', false);
        } else {
            $max = max(1, max(array_map(fn($p) => $p->getViewCount(), $projects)));
            echo '<p class="muted">Most viewed first. A view is counted each time a customer opens the project.</p>
                  <table><tr><th>Project</th><th>Stage</th><th>Category</th><th style="width:40%">Views</th></tr>';
            foreach ($projects as $p) {
                echo '<tr><td>' . e($p->getTitle()) . '</td><td>' . e($p->getStage()) . '</td><td>' . e($p->getCategory()) . '</td>'
                   . '<td><strong>' . $p->getViewCount() . '</strong><div class="bar"><span style="width:'
                   . round($p->getViewCount() / $max * 100) . '%"></span></div></td></tr>';
            }
            echo '</table>';
        }
        renderFooter();
    }
}

startSession('Interior Designer');
(new ProjectAnalyticsPage())->displayProjectStats(currentUserId());
