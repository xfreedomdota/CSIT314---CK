<?php
require_once __DIR__ . '/../Entity/Category.php';

class SearchCategoryController
{
    private Category $category;

    public function __construct(?Category $category = null)
    {
        $this->category = $category ?? new Category();
    }

    public function searchCategories(string $keyword): array
    {
        return $this->category->searchCategoriesByKeyword($keyword);
    }
}
