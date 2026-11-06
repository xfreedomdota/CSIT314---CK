<?php
require_once __DIR__ . '/../Entity/Category.php';

class UpdateCategoryController
{
    private Category $category;

    public function __construct(?Category $category = null)
    {
        $this->category = $category ?? new Category();
    }

    public function updateCategory(int $categoryID, string $categoryName, string $description): bool
    {
        if (trim($categoryName) === '') {
            return false;
        }
        return $this->category->updateCategory($categoryID, trim($categoryName), trim($description));
    }
}
