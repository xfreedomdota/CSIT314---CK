<?php
require_once __DIR__ . '/IDPController.php';

class AddPastIDPController extends IDPController
{
    public function addPastIDP(int $designerID, string $title, string $category, string $description, ?array $photos = null): bool
    {
        $this->error = '';
        if (!$this->validate($title, $category, 'Completed', 100)) {
            return false;
        }
        $photo = $this->savePhoto($photos);
        if ($photo === false) {
            return false;
        }
        return $this->idp->createPastIDP($designerID, $title, $category, $description, $photo ?? '');
    }
}
