<?php
require_once __DIR__ . '/../Database/Database.php';

class UserAccount
{
    private PDO $db;
    private ?int $userId;
    private string $username;
    private string $password;
    private string $email;
    private string $status;

    public function __construct(
        ?PDO $db = null,
        ?int $userId = null,
        string $username = '',
        string $password = '',
        string $email = '',
        string $status = 'Active'
    ) {
        $this->db       = $db ?? Database::connect();
        $this->userId   = $userId;
        $this->username = $username;
        $this->password = $password;
        $this->email    = $email;
        $this->status   = $status;
    }

    public function getUserId(): ?int    { return $this->userId; }
    public function getUsername(): string { return $this->username; }
    public function getEmail(): string    { return $this->email; }
    public function getStatus(): string   { return $this->status; }

    public function validatePassword(string $username, string $password): bool
    {
        $stmt = $this->db->prepare('SELECT password, status FROM useraccount WHERE username = ?');
        $stmt->execute([$username]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row || $row['status'] === 'Suspended') {
            return false;
        }
        return password_verify($password, $row['password']);
    }

    public function createAccount(string $username, string $password, string $email): bool
    {
        if ($this->searchAccount($username) !== null) {
            return false;
        }
        $stmt = $this->db->prepare(
            'INSERT INTO useraccount (username, password, email, status) VALUES (?, ?, ?, ?)'
        );
        return $stmt->execute([$username, password_hash($password, PASSWORD_DEFAULT), $email, 'Active']);
    }

    public function searchAccount(string $username): ?UserAccount
    {
        $stmt = $this->db->prepare(
            'SELECT userId, username, password, email, status FROM useraccount WHERE username = ?'
        );
        $stmt->execute([$username]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? $this->fromRow($row) : null;
    }

    public function updateAccount(string $username, string $password, string $email, string $status): bool
    {
        if ($this->searchAccount($username) === null) {
            return false;
        }
        if ($password === '') {
            $stmt = $this->db->prepare('UPDATE useraccount SET email = ?, status = ? WHERE username = ?');
            return $stmt->execute([$email, $status, $username]);
        }
        $stmt = $this->db->prepare(
            'UPDATE useraccount SET password = ?, email = ?, status = ? WHERE username = ?'
        );
        return $stmt->execute([password_hash($password, PASSWORD_DEFAULT), $email, $status, $username]);
    }

    public function suspendAccount(string $username): bool
    {
        $account = $this->searchAccount($username);
        if ($account === null || $account->getStatus() === 'Suspended') {
            return false;
        }
        $stmt = $this->db->prepare("UPDATE useraccount SET status = 'Suspended' WHERE username = ?");
        return $stmt->execute([$username]);
    }

    public function searchAccountsByKeyword(string $keyword): array
    {
        $id   = ctype_digit($keyword) ? (int) $keyword : -1;
        $like = '%' . $keyword . '%';

        $stmt = $this->db->prepare(
            'SELECT userId, username, password, email, status FROM useraccount
             WHERE userId = ? OR username LIKE ? OR email LIKE ?
             ORDER BY username'
        );
        $stmt->execute([$id, $like, $like]);

        $accounts = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $accounts[] = $this->fromRow($row);
        }
        return $accounts;
    }

    private function fromRow(array $row): UserAccount
    {
        return new UserAccount(
            $this->db,
            isset($row['userId']) ? (int) $row['userId'] : null,
            $row['username'],
            $row['password'],
            $row['email'],
            $row['status']
        );
    }
}
