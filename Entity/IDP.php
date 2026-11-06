<?php
require_once __DIR__ . '/../Database/Database.php';

class IDP
{
    public const STATUSES = ['Planning', 'In Progress', 'Completed', 'On Hold'];

    private PDO $db;
    private ?int $projectID;
    private string $title;
    private string $category;
    private string $description;
    private string $photo;
    private int $designerID;
    private string $status;
    private int $progress;
    private string $stage;
    private string $designerName;
    private string $companyName;
    private int $viewCount = 0;
    private int $shortlistCount = 0;

    public function __construct(?PDO $db = null, array $row = [])
    {
        $this->db           = $db ?? Database::connect();
        $this->projectID    = isset($row['projectID']) ? (int) $row['projectID'] : null;
        $this->title        = $row['title'] ?? '';
        $this->category     = $row['category'] ?? '';
        $this->description  = $row['description'] ?? '';
        $this->photo        = (string) ($row['photos'] ?? '');
        $this->designerID   = (int) ($row['designerID'] ?? 0);
        $this->status       = $row['status'] ?? '';
        $this->progress     = (int) ($row['progress'] ?? 0);
        $this->stage        = $row['stage'] ?? 'Current';
        $this->designerName = $row['designerName'] ?? '';
        $this->companyName  = $row['companyName'] ?? '';
    }

    public function getProjectID(): ?int       { return $this->projectID; }
    public function getTitle(): string         { return $this->title; }
    public function getCategory(): string      { return $this->category; }
    public function getDescription(): string   { return $this->description; }
    public function getPhoto(): string         { return $this->photo; }
    public function getDesignerID(): int       { return $this->designerID; }
    public function getStatus(): string        { return $this->status; }
    public function getProgress(): int         { return $this->progress; }
    public function getStage(): string         { return $this->stage; }
    public function getDesignerName(): string  { return $this->designerName; }
    public function getCompanyName(): string   { return $this->companyName; }
    public function getViewCount(): int        { return $this->viewCount; }
    public function getShortlistCount(): int   { return $this->shortlistCount; }

    private const SELECT = 'SELECT i.projectID, i.title, i.category, i.description, i.photos, i.designerID,
                                   i.status, i.progress, i.stage, p.name AS designerName, c.companyName
                            FROM idp i
                            LEFT JOIN useraccount a ON a.userId = i.designerID
                            LEFT JOIN userprofile p ON p.username = a.username
                            LEFT JOIN company c ON c.designerID = i.designerID';

    public function searchIDP(string $keyword, string $category): array
    {
        return $this->find($keyword, $category, '', null, null);
    }

    public function searchIDPByID(int $projectID): ?IDP
    {
        $stmt = $this->db->prepare(self::SELECT . ' WHERE i.projectID = ?');
        $stmt->execute([$projectID]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? new IDP($this->db, $row) : null;
    }

    public function recordView(int $projectID): bool
    {
        $stmt = $this->db->prepare('INSERT INTO project_views (projectID, viewedAt) VALUES (?, ?)');
        return $stmt->execute([$projectID, date('Y-m-d H:i:s')]);
    }

    public function createIDP(int $designerID, string $title, string $category, string $description,
                              string $photo, string $status, int $progress): bool
    {
        return $this->insert($designerID, $title, $category, $description, $photo, $status, $progress, 'Current');
    }

    public function searchCurrentIDPByDesigner(int $designerID): array
    {
        return $this->find('', '', '', $designerID, 'Current');
    }

    public function UpdateIDP(int $projectID, int $designerID, string $title, string $category, string $description,
                              ?string $photo, string $status, int $progress): bool
    {
        return $this->update($projectID, $designerID, 'Current', $title, $category, $description, $photo, $status, $progress);
    }

    public function DeleteIDP(int $projectID, int $designerID): bool
    {
        return $this->delete($projectID, $designerID, 'Current');
    }

    public function searchIDPByCondition(int $designerID, string $keyword, string $status): array
    {
        return $this->find($keyword, '', $status, $designerID, 'Current');
    }

    public function createPastIDP(int $designerID, string $title, string $category, string $description, string $photo): bool
    {
        return $this->insert($designerID, $title, $category, $description, $photo, 'Completed', 100, 'Past');
    }

    public function searchPastIDPsByDesigner(int $designerID): array
    {
        return $this->find('', '', '', $designerID, 'Past');
    }

    public function updatePastIDP(int $projectID, int $designerID, string $title, string $category,
                                  string $description, ?string $photo): bool
    {
        return $this->update($projectID, $designerID, 'Past', $title, $category, $description, $photo, 'Completed', 100);
    }

    public function deletePastIDP(int $projectID, int $designerID): bool
    {
        return $this->delete($projectID, $designerID, 'Past');
    }

    public function searchPastIDPsByCondition(int $designerID, string $keyword, string $style): array
    {
        return $this->find($keyword, $style, '', $designerID, 'Past');
    }

    public function fetchViewCount(int $designerID): array
    {
        return $this->withCounts($designerID, 'SELECT COUNT(*) FROM project_views WHERE projectID = ?', 'viewCount');
    }

    public function fetchShortlistCount(int $designerID): array
    {
        return $this->withCounts($designerID, 'SELECT COUNT(*) FROM shortlist WHERE projectID = ?', 'shortlistCount');
    }

    public function isOwnedBy(int $projectID, int $designerID, string $stage): bool
    {
        $idp = $this->searchIDPByID($projectID);
        return $idp !== null && $idp->getDesignerID() === $designerID && $idp->getStage() === $stage;
    }

    private function find(string $keyword, string $category, string $status, ?int $designerID, ?string $stage): array
    {
        $sql    = self::SELECT . ' WHERE 1 = 1';
        $params = [];
        if ($keyword !== '') {
            $sql .= ' AND (i.title LIKE ? OR i.description LIKE ? OR i.category LIKE ?)';
            $like = '%' . $keyword . '%';
            array_push($params, $like, $like, $like);
        }
        if ($category !== '')     { $sql .= ' AND i.category = ?';   $params[] = $category; }
        if ($status !== '')       { $sql .= ' AND i.status = ?';     $params[] = $status; }
        if ($designerID !== null) { $sql .= ' AND i.designerID = ?'; $params[] = $designerID; }
        if ($stage !== null)      { $sql .= ' AND i.stage = ?';      $params[] = $stage; }
        $sql .= ' ORDER BY i.projectID DESC';

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return array_map(fn($row) => new IDP($this->db, $row), $stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    private function insert(int $designerID, string $title, string $category, string $description,
                            string $photo, string $status, int $progress, string $stage): bool
    {
        $now  = date('Y-m-d H:i:s');
        $stmt = $this->db->prepare(
            'INSERT INTO idp (title, category, description, photos, designerID, status, progress, stage, createdAt, updatedAt)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
        );
        return $stmt->execute([$title, $category, $description, $photo, $designerID, $status, $progress, $stage, $now, $now]);
    }

    private function update(int $projectID, int $designerID, string $stage, string $title, string $category,
                            string $description, ?string $photo, string $status, int $progress): bool
    {
        if (!$this->isOwnedBy($projectID, $designerID, $stage)) {
            return false;
        }
        $now = date('Y-m-d H:i:s');
        if ($photo === null) {
            $stmt = $this->db->prepare(
                'UPDATE idp SET title = ?, category = ?, description = ?, status = ?, progress = ?, updatedAt = ? WHERE projectID = ?'
            );
            return $stmt->execute([$title, $category, $description, $status, $progress, $now, $projectID]);
        }
        $stmt = $this->db->prepare(
            'UPDATE idp SET title = ?, category = ?, description = ?, photos = ?, status = ?, progress = ?, updatedAt = ? WHERE projectID = ?'
        );
        return $stmt->execute([$title, $category, $description, $photo, $status, $progress, $now, $projectID]);
    }

    private function delete(int $projectID, int $designerID, string $stage): bool
    {
        if (!$this->isOwnedBy($projectID, $designerID, $stage)) {
            return false;
        }
        $this->db->prepare('DELETE FROM project_views WHERE projectID = ?')->execute([$projectID]);
        $this->db->prepare('DELETE FROM shortlist WHERE projectID = ?')->execute([$projectID]);
        return $this->db->prepare('DELETE FROM idp WHERE projectID = ?')->execute([$projectID]);
    }

    private function withCounts(int $designerID, string $countSql, string $field): array
    {
        $stmt = $this->db->prepare($countSql);
        $list = $this->find('', '', '', $designerID, null);
        foreach ($list as $idp) {
            $stmt->execute([$idp->getProjectID()]);
            $idp->$field = (int) $stmt->fetchColumn();
        }
        usort($list, fn($a, $b) => $b->$field <=> $a->$field);
        return $list;
    }
}
