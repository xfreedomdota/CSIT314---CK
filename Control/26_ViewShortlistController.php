<?php
require_once __DIR__ . '/../Entity/Shortlist.php';

class ViewShortlistController
{
    private Shortlist $shortlist;

    public function __construct(?Shortlist $shortlist = null)
    {
        $this->shortlist = $shortlist ?? new Shortlist();
    }

    public function getShortlistedIDP(int $customerId): array
    {
        return $this->shortlist->getIDP($customerId);
    }
}
