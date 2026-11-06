<?php
require_once __DIR__ . '/../Entity/Category.php';

class CreateCategoryController
{
    private Category $category;
    private string $error = '';

    public function __construct(?Category $category = null)
    {
        $this->category = $category ?? new Category();
    }

    public function getError(): string { return $this->error; }

    public function createCategory(string $categoryName, string $description): bool
    {
        $this->error = '';
        if ($categoryName === '') {
            $this->error = 'invalid';
            return false;
        }
        if (!$this->category->createCategory($categoryName, $description)) {
            $this->error = 'exists';
            return false;
        }
        return true;
    }
}
