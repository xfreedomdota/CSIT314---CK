<?php
require_once __DIR__ . '/../Entity/IDP.php';

abstract class IDPController
{
    public const MAX_PHOTO_BYTES = 5 * 1024 * 1024;
    public const PHOTO_TYPES     = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp', 'image/gif' => 'gif'];

    protected IDP $idp;
    protected string $error = '';

    public function __construct(?IDP $idp = null)
    {
        $this->idp = $idp ?? new IDP();
    }

    public function getError(): string { return $this->error; }

    public function getOwnIDP(int $projectID, int $designerID, string $stage): ?IDP
    {
        return $this->idp->isOwnedBy($projectID, $designerID, $stage) ? $this->idp->searchIDPByID($projectID) : null;
    }

    protected function validate(string $title, string $category, string $status, int $progress): bool
    {
        if (trim($title) === '' || trim($category) === '' || !in_array($status, IDP::STATUSES, true)
            || $progress < 0 || $progress > 100) {
            $this->error = 'Please fill in the title and category, and use a progress between 0 and 100.';
            return false;
        }
        return true;
    }

    protected function savePhoto(?array $file): string|false|null
    {
        if ($file === null || ($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            return null;
        }
        $info = $file['error'] === UPLOAD_ERR_OK ? @getimagesize($file['tmp_name']) : false;
        if ($file['size'] > self::MAX_PHOTO_BYTES || !$info || !isset(self::PHOTO_TYPES[$info['mime']])) {
            $this->error = 'Photo must be JPG, PNG, WEBP or GIF and 5 MB or smaller.';
            return false;
        }
        $name = 'uploads/' . bin2hex(random_bytes(8)) . '.' . self::PHOTO_TYPES[$info['mime']];
        if (!move_uploaded_file($file['tmp_name'], __DIR__ . '/../Assets/' . $name)) {
            $this->error = 'Photo could not be saved.';
            return false;
        }
        return $name;
    }
}
