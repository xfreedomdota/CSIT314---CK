<?php
require_once __DIR__ . '/IDPController.php';

class SearchCurrentIDPController extends IDPController
{
    public function searchIDP(int $designerID, string $keyword, string $status): array
    {
        return $this->idp->searchIDPByCondition($designerID, trim($keyword), trim($status));
    }
}
