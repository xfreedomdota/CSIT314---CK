<?php
require_once __DIR__ . '/../Database/Database.php';

class UserProfile
{
    public const USER_TYPES = ['User Admin', 'Interior Designer', 'Customer', 'Platform Manager'];

    private PDO $db;
    private string $username;
    private string $password;
    private string $name;
    private string $userType;
    private string $status;

    public function __construct(
        ?PDO $db = null,
        string $username = '',
        string $password = '',
        string $name = '',
        string $userType = '',
        string $status = 'Active'
    ) {
        $this->db       = $db ?? Database::connect();
        $this->username = $username;
        $this->password = $password;
        $this->name     = $name;
        $this->userType = $userType;
        $this->status   = $status;
    }

    public function getUsername(): string { return $this->username; }
    public function getName(): string     { return $this->name; }
    public function getUserType(): string { return $this->userType; }
    public function getStatus(): string   { return $this->status; }

    public function saveProfile(string $username, string $password, string $name, string $userType): bool
    {
        if ($this->getProfile($username) !== null) {
            return false;
        }
        $stmt = $this->db->prepare(
            'INSERT INTO userprofile (username, password, name, type, status) VALUES (?, ?, ?, ?, ?)'
        );
        return $stmt->execute([$username, password_hash($password, PASSWORD_DEFAULT), $name, $userType, 'Active']);
    }

    public function getProfile(string $username): ?UserProfile
    {
        $stmt = $this->db->prepare(
            'SELECT username, password, name, type, status FROM userprofile WHERE username = ?'
        );
        $stmt->execute([$username]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? $this->fromRow($row) : null;
    }

    public function updateProfile(string $username, string $password, string $name, string $userType): bool
    {
        if ($this->getProfile($username) === null) {
            return false;
        }
        if ($password === '') {
            $stmt = $this->db->prepare('UPDATE userprofile SET name = ?, type = ? WHERE username = ?');
            return $stmt->execute([$name, $userType, $username]);
        }
        $stmt = $this->db->prepare(
            'UPDATE userprofile SET password = ?, name = ?, type = ? WHERE username = ?'
        );
        return $stmt->execute([password_hash($password, PASSWORD_DEFAULT), $name, $userType, $username]);
    }

    public function suspendProfile(string $username): bool
    {
        if ($this->getProfile($username) === null) {
            return false;
        }
        $stmt = $this->db->prepare("UPDATE userprofile SET status = 'Suspended' WHERE username = ?");
        return $stmt->execute([$username]);
    }

    public function searchProfilesByFilter(string $filter): array
    {
        $like = '%' . $filter . '%';
        $stmt = $this->db->prepare(
            'SELECT username, password, name, type, status FROM userprofile
             WHERE username LIKE ? OR name LIKE ? OR type LIKE ?
             ORDER BY username'
        );
        $stmt->execute([$like, $like, $like]);

        $profiles = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $profiles[] = $this->fromRow($row);
        }
        return $profiles;
    }

    private function fromRow(array $row): UserProfile
    {
        return new UserProfile(
            $this->db,
            $row['username'],
            $row['password'],
            $row['name'],
            $row['type'],
            $row['status'] ?? 'Active'
        );
    }
}
