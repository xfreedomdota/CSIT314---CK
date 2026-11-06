<?php
require_once __DIR__ . '/../Entity/UserSession.php';

class LogOutController
{
    protected UserSession $userSession;

    public function __construct(?UserSession $userSession = null)
    {
        $this->userSession = $userSession ?? new UserSession();
    }

    public function logout(): bool
    {
        $this->userSession->terminate();
        return true;
    }
}
