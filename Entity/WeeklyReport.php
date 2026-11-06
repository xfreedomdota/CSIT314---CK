<?php
require_once __DIR__ . '/Report.php';

class WeeklyReport extends Report
{
    public function compileWeeklySummary(): ?WeeklyReport
    {
        return $this->compile('weekly');
    }
}
