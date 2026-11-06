<?php
require_once __DIR__ . '/IDPController.php';

class UpdatePastIDPController extends IDPController
{
    public function updatePastIDP(int $projectID, int $designerID, string $title, string $category, string $description,
                                  ?array $photos = null): bool
    {
        $this->error = '';
        if (!$this->validate($title, $category, 'Completed', 100)) {
            return false;
        }
        $photo = $this->savePhoto($photos);
        if ($photo === false) {
            return false;
        }
        if (!$this->idp->updatePastIDP($projectID, $designerID, trim($title), $category, trim($description), $photo)) {
            $this->error = 'Project not found.';
            return false;
        }
        return true;
    }
}
