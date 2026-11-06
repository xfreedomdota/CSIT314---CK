<?php
require_once __DIR__ . '/IDPController.php';

class DeleteCurrentIDPController extends IDPController
{
    public function DeleteIDP(int $projectID, int $designerID): bool
    {
        return $this->idp->DeleteIDP($projectID, $designerID);
    }
}
