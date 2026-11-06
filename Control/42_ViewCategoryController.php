<?php
require_once __DIR__ . '/../Entity/Category.php';

class ViewCategoryController
{
    private Category $category;

    public function __construct(?Category $category = null)
    {
        $this->category = $category ?? new Category();
    }

    public function getCategoryDetails(int $categoryID): ?Category
    {
        return $this->category->searchCategoryByID($categoryID);
    }

    public function countProjects(Category $found): int
    {
        return $this->category->countProjects($found->getCategoryName());
    }
}
