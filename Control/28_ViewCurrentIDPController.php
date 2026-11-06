<?php
require_once __DIR__ . '/IDPController.php';

class ViewCurrentIDPController extends IDPController
{
    public function getCurrentIDP(int $designerID): array
    {
        return $this->idp->searchCurrentIDPByDesigner($designerID);
    }
}
