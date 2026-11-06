<?php
require_once __DIR__ . '/../Database/Database.php';
require_once __DIR__ . '/IDP.php';

class Shortlist
{
    private PDO $db;
    private int $customerID;

    public function __construct(?PDO $db = null, int $customerID = 0)
    {
        $this->db         = $db ?? Database::connect();
        $this->customerID = $customerID;
    }

    public function setCustomerID(int $customerID): void { $this->customerID = $customerID; }
    public function getCustomerID(): int                { return $this->customerID; }

    public function addProject(int $projectID): bool
    {
        if ($this->contains($projectID)) {
            return false;
        }
        $stmt = $this->db->prepare('INSERT INTO shortlist (customerID, projectID, createdAt) VALUES (?, ?, ?)');
        return $stmt->execute([$this->customerID, $projectID, date('Y-m-d H:i:s')]);
    }

    public function contains(int $projectID): bool
    {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM shortlist WHERE customerID = ? AND projectID = ?');
        $stmt->execute([$this->customerID, $projectID]);
        return (int) $stmt->fetchColumn() > 0;
    }

    public function searchIDPByFilter(string $keyword, string $filter): array
    {
        $saved = $this->projectIDs();
        $idp   = new IDP($this->db);
        return array_values(array_filter($idp->searchIDP($keyword, $filter),
                                         fn(IDP $p) => in_array($p->getProjectID(), $saved, true)));
    }

    public function getIDP(int $customerID): array
    {
        $this->customerID = $customerID;
        $idp  = new IDP($this->db);
        $list = [];
        foreach ($this->projectIDs() as $projectID) {
            $found = $idp->searchIDPByID($projectID);
            if ($found !== null) {
                $list[] = $found;
            }
        }
        return $list;
    }

    private function projectIDs(): array
    {
        $stmt = $this->db->prepare('SELECT projectID FROM shortlist WHERE customerID = ? ORDER BY createdAt DESC');
        $stmt->execute([$this->customerID]);
        return array_map('intval', $stmt->fetchAll(PDO::FETCH_COLUMN));
    }
}
