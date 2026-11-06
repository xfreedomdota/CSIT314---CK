<?php
require_once __DIR__ . '/IDPController.php';

class UpdateCurrentIDPController extends IDPController
{
    public function UpdateIDP(int $projectID, int $designerID, string $title, string $category, string $description,
                              string $status, int $progress, ?array $photos = null): bool
    {
        $this->error = '';
        if (!$this->validate($title, $category, $status, $progress)) {
            return false;
        }
        $photo = $this->savePhoto($photos);
        if ($photo === false) {
            return false;
        }
        if (!$this->idp->UpdateIDP($projectID, $designerID, $title, $category, $description, $photo, $status, $progress)) {
            $this->error = 'Project not found.';
            return false;
        }
        return true;
    }
}
