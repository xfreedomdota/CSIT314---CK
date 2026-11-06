<?php
require_once __DIR__ . '/layout.php';
require_once __DIR__ . '/../Control/48_MonthlyReportController.php';

class MonthlyReportPage
{
    private ?Report $report = null;

    public function displayReportPage(): void
    {
        $controller = new MonthlyReportController();

        renderHeader('Monthly Report');
        echo '<p class="muted noprint">Overall platform analytics for the last 30 days, ready to print for stakeholders.</p>
              <form method="post"><button type="submit">Generate monthly report</button></form>';
        if ($this->report !== null) {
            renderReport($this->report);
        }
        renderPreviousReports($controller->getPreviousReports());
        renderFooter();
    }

    public function generateMonthlyReport(): void
    {
        $this->report = (new MonthlyReportController())->generateMonthlyReport();
        $this->displayReportPage();
    }
}

startSession('Platform Manager');
$page = new MonthlyReportPage();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $page->generateMonthlyReport();
} else {
    $page->displayReportPage();
}
