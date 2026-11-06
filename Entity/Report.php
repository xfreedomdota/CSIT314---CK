<?php
require_once __DIR__ . '/../Database/Database.php';

class Report
{
    public const PERIODS = ['daily' => '-1 day', 'weekly' => '-7 days', 'monthly' => '-30 days'];

    private PDO $db;
    private ?int $reportID;
    private string $reportType;
    private string $generationDate;
    private array $summary;

    public function __construct(?PDO $db = null, ?int $reportID = null, string $reportType = '',
                                string $generationDate = '', array $summary = [])
    {
        $this->db             = $db ?? Database::connect();
        $this->reportID       = $reportID;
        $this->reportType     = $reportType;
        $this->generationDate = $generationDate;
        $this->summary        = $summary;
    }

    public function getReportID(): ?int         { return $this->reportID; }
    public function getReportType(): string     { return $this->reportType; }
    public function getGenerationDate(): string { return $this->generationDate; }
    public function getSummary(): array         { return $this->summary; }

    public function compileDailySummary(): ?Report
    {
        return $this->compile('daily');
    }

    protected function compile(string $reportType): ?static
    {
        if (!isset(self::PERIODS[$reportType])) {
            return null;
        }
        $since = date('Y-m-d H:i:s', strtotime(self::PERIODS[$reportType]));
        $now   = date('Y-m-d H:i:s');

        $summary = [
            'From'                => $since,
            'To'                  => $now,
            'Project views'       => $this->count('SELECT COUNT(*) FROM project_views WHERE viewedAt >= ?', [$since]),
            'Projects shortlisted'=> $this->count('SELECT COUNT(*) FROM shortlist WHERE createdAt >= ?', [$since]),
            'New projects'        => $this->count('SELECT COUNT(*) FROM idp WHERE createdAt >= ?', [$since]),
            'Most viewed category'=> $this->topCategory($since),
            'Total projects'      => $this->count('SELECT COUNT(*) FROM idp', []),
            'Active accounts'     => $this->count("SELECT COUNT(*) FROM useraccount WHERE status = 'Active'", []),
        ];

        $stmt = $this->db->prepare('INSERT INTO report (reportType, generationDate, summaryData) VALUES (?, ?, ?)');
        $stmt->execute([$reportType, $now, json_encode($summary)]);

        return new static($this->db, (int) $this->db->lastInsertId(), $reportType, $now, $summary);
    }

    public function getRecentReports(string $reportType, int $limit = 10): array
    {
        $stmt = $this->db->prepare(
            'SELECT reportID, reportType, generationDate, summaryData FROM report
             WHERE reportType = ? ORDER BY reportID DESC LIMIT ' . (int) $limit
        );
        $stmt->execute([$reportType]);

        $reports = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $reports[] = new static($this->db, (int) $row['reportID'], $row['reportType'],
                                    $row['generationDate'], json_decode($row['summaryData'], true) ?? []);
        }
        return $reports;
    }

    private function count(string $sql, array $params): int
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn();
    }

    private function topCategory(string $since): string
    {
        $stmt = $this->db->prepare(
            'SELECT i.category, COUNT(*) AS n FROM project_views v JOIN idp i ON i.projectID = v.projectID
             WHERE v.viewedAt >= ? GROUP BY i.category ORDER BY n DESC LIMIT 1'
        );
        $stmt->execute([$since]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $row['category'] . ' (' . $row['n'] . ' views)' : 'None';
    }
}
