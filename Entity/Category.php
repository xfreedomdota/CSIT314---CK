<?php
require_once __DIR__ . '/../Database/Database.php';

class Category
{
    private PDO $db;
    private ?int $categoryID;
    private string $categoryName;
    private string $description;

    public function __construct(?PDO $db = null, ?int $categoryID = null, string $categoryName = '', string $description = '')
    {
        $this->db           = $db ?? Database::connect();
        $this->categoryID   = $categoryID;
        $this->categoryName = $categoryName;
        $this->description  = $description;
    }

    public function getCategoryID(): ?int      { return $this->categoryID; }
    public function getCategoryName(): string  { return $this->categoryName; }
    public function getDescription(): string   { return $this->description; }

    public function createCategory(string $categoryName, string $description): bool
    {
        if ($this->nameTaken($categoryName, null)) {
            return false;
        }
        $stmt = $this->db->prepare('INSERT INTO category (categoryName, description) VALUES (?, ?)');
        return $stmt->execute([$categoryName, $description]);
    }

    public function searchCategoryByID(int $categoryID): ?Category
    {
        $stmt = $this->db->prepare('SELECT categoryID, categoryName, description FROM category WHERE categoryID = ?');
        $stmt->execute([$categoryID]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $this->fromRow($row) : null;
    }

    public function updateCategory(int $categoryID, string $categoryName, string $description): bool
    {
        if ($this->searchCategoryByID($categoryID) === null || $this->nameTaken($categoryName, $categoryID)) {
            return false;
        }
        $stmt = $this->db->prepare('UPDATE category SET categoryName = ?, description = ? WHERE categoryID = ?');
        return $stmt->execute([$categoryName, $description, $categoryID]);
    }

    public function deleteCategory(int $categoryID): bool
    {
        if ($this->searchCategoryByID($categoryID) === null) {
            return false;
        }
        return $this->db->prepare('DELETE FROM category WHERE categoryID = ?')->execute([$categoryID]);
    }

    public function searchCategoriesByKeyword(string $keyword): array
    {
        $like = '%' . $keyword . '%';
        $stmt = $this->db->prepare(
            'SELECT categoryID, categoryName, description FROM category
             WHERE categoryName LIKE ? OR description LIKE ? ORDER BY categoryName'
        );
        $stmt->execute([$like, $like]);
        return array_map(fn($row) => $this->fromRow($row), $stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function countProjects(string $categoryName): int
    {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM idp WHERE category = ?');
        $stmt->execute([$categoryName]);
        return (int) $stmt->fetchColumn();
    }

    private function nameTaken(string $categoryName, ?int $exceptID): bool
    {
        $stmt = $this->db->prepare('SELECT categoryID FROM category WHERE LOWER(categoryName) = LOWER(?)');
        $stmt->execute([$categoryName]);
        foreach ($stmt->fetchAll(PDO::FETCH_COLUMN) as $id) {
            if ((int) $id !== $exceptID) {
                return true;
            }
        }
        return false;
    }

    private function fromRow(array $row): Category
    {
        return new Category($this->db, (int) $row['categoryID'], $row['categoryName'], $row['description']);
    }
}
