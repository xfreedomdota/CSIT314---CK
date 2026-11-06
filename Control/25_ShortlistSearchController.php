<?php
require_once __DIR__ . '/../Entity/Shortlist.php';

class ShortlistSearchController
{
    private Shortlist $shortlist;

    public function __construct(?Shortlist $shortlist = null)
    {
        $this->shortlist = $shortlist ?? new Shortlist();
    }

    public function searchShortlist(int $customerId, string $keyword, string $filter): array
    {
        $this->shortlist->setCustomerID($customerId);
        return $this->shortlist->searchIDPByFilter($keyword, $filter);
    }
}
