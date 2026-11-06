<?php
require_once __DIR__ . '/../Entity/IDP.php';

class ProjectAnalyticsController
{
    private IDP $idp;

    public function __construct(?IDP $idp = null)
    {
        $this->idp = $idp ?? new IDP();
    }

    public function getIDPViewCount(int $designerID): array
    {
        return $this->idp->fetchViewCount($designerID);
    }
}
