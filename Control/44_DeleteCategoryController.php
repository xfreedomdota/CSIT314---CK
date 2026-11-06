<?php
require_once __DIR__ . '/../Entity/Category.php';

class DeleteCategoryController
{
    private Category $category;
    private string $error = '';

    public function __construct(?Category $category = null)
    {
        $this->category = $category ?? new Category();
    }

    public function getError(): string { return $this->error; }

    public function deleteCategory(int $categoryID): bool
    {
        $this->error = '';
        $found = $this->category->searchCategoryByID($categoryID);
        if ($found === null) {
            $this->error = 'not_found';
            return false;
        }
        if ($this->category->countProjects($found->getCategoryName()) > 0) {
            $this->error = 'in_use';
            return false;
        }
        return $this->category->deleteCategory($categoryID);
    }
}
