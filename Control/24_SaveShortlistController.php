<?php
require_once __DIR__ . '/../Entity/Shortlist.php';
require_once __DIR__ . '/../Entity/IDP.php';

class SaveShortlistController
{
    private Shortlist $shortlist;
    private IDP $idp;
    private string $error = '';

    public function __construct(?Shortlist $shortlist = null, ?IDP $idp = null)
    {
        $this->shortlist = $shortlist ?? new Shortlist();
        $this->idp       = $idp ?? new IDP();
    }

    public function getError(): string { return $this->error; }

    public function saveIDPToShortlist(int $customerId, int $projectId): bool
    {
        $this->error = '';
        if ($this->idp->searchIDPByID($projectId) === null) {
            $this->error = 'not_found';
            return false;
        }
        $this->shortlist->setCustomerID($customerId);
        if (!$this->shortlist->addProject($projectId)) {
            $this->error = 'exists';
            return false;
        }
        return true;
    }
}
