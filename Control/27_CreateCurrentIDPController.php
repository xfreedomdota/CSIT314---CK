<?php
require_once __DIR__ . '/IDPController.php';

class CreateCurrentIDPController extends IDPController
{
    public function createIDP(int $designerID, string $title, string $category, string $description,
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
        return $this->idp->createIDP($designerID, $title, $category, $description, $photo ?? '', $status, $progress);
    }
}
