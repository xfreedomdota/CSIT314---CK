<?php
require_once __DIR__ . '/../Entity/Report.php';

class DailyReportController
{
    private Report $report;

    public function __construct(?Report $report = null)
    {
        $this->report = $report ?? new Report();
    }

    public function generateDailyReport(): ?Report
    {
        return $this->report->compileDailySummary();
    }

    public function getPreviousReports(): array
    {
        return $this->report->getRecentReports('daily');
    }
}
