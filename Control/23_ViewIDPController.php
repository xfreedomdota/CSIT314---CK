<?php
require_once __DIR__ . '/../Entity/IDP.php';

class ViewIDPController
{
    private IDP $idp;

    public function __construct(?IDP $idp = null)
    {
        $this->idp = $idp ?? new IDP();
    }

    public function getIDP(int $projectID): ?IDP
    {
        $found = $this->idp->searchIDPByID($projectID);
        if ($found !== null) {
            $this->idp->recordView($projectID);
        }
        return $found;
    }
}
