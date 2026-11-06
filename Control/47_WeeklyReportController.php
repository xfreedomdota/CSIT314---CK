<?php
require_once __DIR__ . '/../Entity/WeeklyReport.php';

class WeeklyReportController
{
    private WeeklyReport $report;

    public function __construct(?WeeklyReport $report = null)
    {
        $this->report = $report ?? new WeeklyReport();
    }

    public function generateWeeklyReport(): ?WeeklyReport
    {
        return $this->report->compileWeeklySummary();
    }

    public function getPreviousReports(): array
    {
        return $this->report->getRecentReports('weekly');
    }
}
