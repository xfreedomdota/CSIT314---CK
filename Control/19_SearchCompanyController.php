<?php
require_once __DIR__ . '/../Entity/Company.php';

class SearchCompanyController
{
    private Company $company;

    public function __construct(?Company $company = null)
    {
        $this->company = $company ?? new Company();
    }

    public function searchCompanies(string $keyword): array
    {
        return $this->company->searchCompaniesByKeyword($keyword);
    }
}
