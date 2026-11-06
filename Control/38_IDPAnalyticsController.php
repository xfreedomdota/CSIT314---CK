<?php
require_once __DIR__ . '/../Entity/IDP.php';

class IDPAnalyticsController
{
    private IDP $idp;

    public function __construct(?IDP $idp = null)
    {
        $this->idp = $idp ?? new IDP();
    }

    public function getShortlistCount(int $designerID): array
    {
        return $this->idp->fetchShortlistCount($designerID);
    }
}
