<?php
require_once __DIR__ . '/layout.php';
require_once __DIR__ . '/../Control/47_WeeklyReportController.php';

class WeeklyReportPage
{
    private ?Report $report = null;

    public function displayReportPage(): void
    {
        $controller = new WeeklyReportController();

        renderHeader('Weekly Report');
        echo '<p class="muted noprint">Summarises platform performance over the last 7 days, including the most popular category.</p>
              <form method="post"><button type="submit">Generate weekly report</button></form>';
        if ($this->report !== null) {
            renderReport($this->report);
        }
        renderPreviousReports($controller->getPreviousReports());
        renderFooter();
    }

    public function generateWeeklyReport(): void
    {
        $this->report = (new WeeklyReportController())->generateWeeklyReport();
        $this->displayReportPage();
    }
}

startSession('Platform Manager');
$page = new WeeklyReportPage();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $page->generateWeeklyReport();
} else {
    $page->displayReportPage();
}
