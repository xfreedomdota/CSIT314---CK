<?php
require_once __DIR__ . '/../Entity/IDP.php';

class SearchIDPController
{
    private IDP $idp;

    public function __construct(?IDP $idp = null)
    {
        $this->idp = $idp ?? new IDP();
    }

    public function searchIDP(string $keyword, string $category): array
    {
        return $this->idp->searchIDP(trim($keyword), trim($category));
    }
}
