<?php
require_once __DIR__ . '/Report.php';

class MonthlyReport extends Report
{
    public function compileMonthlySummary(): ?MonthlyReport
    {
        return $this->compile('monthly');
    }
}
