<?php
class UserSession
{
    private string $sessionId;
    private string $userId;

    public function __construct()
    {
        $this->sessionId = session_id() ?: '';
        $this->userId    = $_SESSION['username'] ?? '';
    }

    public function getUserId(): string { return $this->userId; }

    public function terminate(): void
    {
        $_SESSION = [];
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }
        $this->sessionId = '';
        $this->userId    = '';
    }
}
