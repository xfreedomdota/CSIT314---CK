<?php
require_once __DIR__ . '/../Entity/MonthlyReport.php';

class MonthlyReportController
{
    private MonthlyReport $report;

    public function __construct(?MonthlyReport $report = null)
    {
        $this->report = $report ?? new MonthlyReport();
    }

    public function generateMonthlyReport(): ?MonthlyReport
    {
        return $this->report->compileMonthlySummary();
    }

    public function getPreviousReports(): array
    {
        return $this->report->getRecentReports('monthly');
    }
}
