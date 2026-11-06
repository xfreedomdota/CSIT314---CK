<?php
require_once __DIR__ . '/layout.php';
require_once __DIR__ . '/../Control/46_DailyReportController.php';

class DailyReportPage
{
    private ?Report $report = null;

    public function displayReportPage(): void
    {
        $controller = new DailyReportController();

        renderHeader('Daily Report');
        echo '<p class="muted noprint">Summarises platform activity in the last 24 hours.</p>
              <form method="post"><button type="submit">Generate daily report</button></form>';
        if ($this->report !== null) {
            renderReport($this->report);
        }
        renderPreviousReports($controller->getPreviousReports());
        renderFooter();
    }

    public function generateDailyReport(): void
    {
        $this->report = (new DailyReportController())->generateDailyReport();
        $this->displayReportPage();
    }
}

startSession('Platform Manager');
$page = new DailyReportPage();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $page->generateDailyReport();
} else {
    $page->displayReportPage();
}
