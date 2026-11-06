<?php
require_once __DIR__ . '/IDPController.php';

class DeletePastIDPController extends IDPController
{
    public function deletePastIDP(int $projectID, int $designerID): bool
    {
        return $this->idp->deletePastIDP($projectID, $designerID);
    }
}
