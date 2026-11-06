<?php
require_once __DIR__ . '/../Database/Database.php';

class Company
{
    private PDO $db;
    private ?int $companyID;
    private string $companyName;
    private string $regNum;
    private string $description;
    private string $address;
    private int $designerID;
    private string $designerName;

    public function __construct(?PDO $db = null, array $row = [])
    {
        $this->db           = $db ?? Database::connect();
        $this->companyID    = isset($row['companyID']) ? (int) $row['companyID'] : null;
        $this->companyName  = $row['companyName'] ?? '';
        $this->regNum       = $row['regNum'] ?? '';
        $this->description  = $row['description'] ?? '';
        $this->address      = $row['address'] ?? '';
        $this->designerID   = (int) ($row['designerID'] ?? 0);
        $this->designerName = $row['designerName'] ?? '';
    }

    public function getCompanyID(): ?int      { return $this->companyID; }
    public function getCompanyName(): string  { return $this->companyName; }
    public function getRegNum(): string       { return $this->regNum; }
    public function getDescription(): string  { return $this->description; }
    public function getAddress(): string      { return $this->address; }
    public function getDesignerID(): int      { return $this->designerID; }
    public function getDesignerName(): string { return $this->designerName; }

    private const SELECT = 'SELECT c.companyID, c.companyName, c.regNum, c.description, c.address, c.designerID,
                                   p.name AS designerName
                            FROM company c
                            LEFT JOIN useraccount a ON a.userId = c.designerID
                            LEFT JOIN userprofile p ON p.username = a.username';

    public function createCompany(int $designerID, string $companyName, string $regNum, string $description, string $address): bool
    {
        if ($this->searchCompany($designerID) !== null) {
            return false;
        }
        $stmt = $this->db->prepare(
            'INSERT INTO company (companyName, regNum, description, address, designerID) VALUES (?, ?, ?, ?, ?)'
        );
        return $stmt->execute([$companyName, $regNum, $description, $address, $designerID]);
    }

    public function searchCompany(int $designerID): ?Company
    {
        $stmt = $this->db->prepare(self::SELECT . ' WHERE c.designerID = ?');
        $stmt->execute([$designerID]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? new Company($this->db, $row) : null;
    }

    public function updateCompany(int $designerID, string $companyName, string $regNum, string $description, string $address): bool
    {
        if ($this->searchCompany($designerID) === null) {
            return false;
        }
        $stmt = $this->db->prepare(
            'UPDATE company SET companyName = ?, regNum = ?, description = ?, address = ? WHERE designerID = ?'
        );
        return $stmt->execute([$companyName, $regNum, $description, $address, $designerID]);
    }

    public function deleteCompany(int $designerID): bool
    {
        if ($this->searchCompany($designerID) === null) {
            return false;
        }
        return $this->db->prepare('DELETE FROM company WHERE designerID = ?')->execute([$designerID]);
    }

    public function searchCompaniesByKeyword(string $keyword): array
    {
        $like = '%' . $keyword . '%';
        $stmt = $this->db->prepare(
            self::SELECT . ' WHERE c.companyName LIKE ? OR c.description LIKE ? OR c.address LIKE ? ORDER BY c.companyName'
        );
        $stmt->execute([$like, $like, $like]);
        return array_map(fn($row) => new Company($this->db, $row), $stmt->fetchAll(PDO::FETCH_ASSOC));
    }
}
