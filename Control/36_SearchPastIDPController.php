<?php
require_once __DIR__ . '/IDPController.php';

class SearchPastIDPController extends IDPController
{
    public function searchPastIDPs(int $designerID, string $keyword, string $style): array
    {
        return $this->idp->searchPastIDPsByCondition($designerID, $keyword, $style);
    }
}
