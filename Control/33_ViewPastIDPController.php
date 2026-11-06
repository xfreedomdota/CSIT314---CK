<?php
require_once __DIR__ . '/IDPController.php';

class ViewPastIDPController extends IDPController
{
    public function getPastIDPs(int $designerID): array
    {
        return $this->idp->searchPastIDPsByDesigner($designerID);
    }
}
