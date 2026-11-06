<?php
const STORIES = [
    1  => ['User Admin', 'Login', '01_UserLoginPage', '01_UserLoginController', 'UserAccount, UserProfile'],
    2  => ['User Admin', 'Logout', '02_UserLogOutPage', '02_UserLogOutController', 'UserSession'],
    3  => ['User Admin', 'Create user profile', '03_CreateProfilePage', '03_CreateProfileController', 'UserProfile'],
    4  => ['User Admin', 'View user profile', '04_ViewProfilePage', '04_ViewProfileController', 'UserProfile'],
    5  => ['User Admin', 'Update user profile', '05_UpdateProfilePage', '05_UpdateProfileController', 'UserProfile'],
    6  => ['User Admin', 'Suspend user profile', '06_SuspendProfilePage', '06_SuspendProfileController', 'UserProfile'],
    7  => ['User Admin', 'Search user profiles', '07_SearchProfilePage', '07_SearchProfileController', 'UserProfile'],
    8  => ['User Admin', 'Create user account', '08_CreateAccountPage', '08_CreateAccountController', 'UserAccount'],
    9  => ['User Admin', 'View user account', '09_ViewAccountPage', '09_ViewAccountController', 'UserAccount'],
    10 => ['User Admin', 'Update user account', '10_UpdateAccountPage', '10_UpdateAccountController', 'UserAccount'],
    11 => ['User Admin', 'Suspend user account', '11_SuspendAccountPage', '11_SuspendAccountController', 'UserAccount'],
    12 => ['User Admin', 'Search user accounts', '12_SearchAccountPage', '12_SearchAccountController', 'UserAccount'],

    13 => ['Interior Designer', 'Login', '13_20_39_LoginPage', '13_20_39_LoginController', 'UserAccount, UserProfile'],
    14 => ['Interior Designer', 'Logout', '14_21_40_LogOutPage', '14_21_40_LogOutController', 'UserSession'],
    15 => ['Interior Designer', 'Register company information', '15_RegisterCompanyPage', '15_RegisterCompanyController', 'Company'],
    16 => ['Interior Designer', 'View company information', '16_ViewCompanyPage', '16_ViewCompanyController', 'Company'],
    17 => ['Interior Designer', 'Update company information', '17_UpdateCompanyPage', '17_UpdateCompanyController', 'Company'],
    18 => ['Interior Designer', 'Delete company information', '18_DeleteCompanyPage', '18_DeleteCompanyController', 'Company'],
    19 => ['Interior Designer', 'Search company information', '19_SearchCompanyPage', '19_SearchCompanyController', 'Company'],

    20 => ['Customer', 'Login', '13_20_39_LoginPage', '13_20_39_LoginController', 'UserAccount, UserProfile'],
    21 => ['Customer', 'Logout', '14_21_40_LogOutPage', '14_21_40_LogOutController', 'UserSession'],
    22 => ['Customer', 'Search IDP by keyword and category', '22_SearchIDPPage', '22_SearchIDPController', 'IDP'],
    23 => ['Customer', 'View IDP', '23_ViewIDPPage', '23_ViewIDPController', 'IDP'],
    24 => ['Customer', 'Save IDP to shortlist', '24_SaveShortlistPage', '24_SaveShortlistController', 'Shortlist, IDP'],
    25 => ['Customer', 'Search and filter shortlist', '25_ShortlistSearchPage', '25_ShortlistSearchController', 'Shortlist'],
    26 => ['Customer', 'View shortlisted IDP side by side', '26_ViewShortlistPage', '26_ViewShortlistController', 'Shortlist'],

    27 => ['Interior Designer', 'Create current IDP', '27_CreateCurrentIDPPage', '27_CreateCurrentIDPController', 'IDP'],
    28 => ['Interior Designer', 'View current IDP', '28_ViewCurrentIDPPage', '28_ViewCurrentIDPController', 'IDP'],
    29 => ['Interior Designer', 'Update current IDP', '29_UpdateCurrentIDPPage', '29_UpdateCurrentIDPController', 'IDP'],
    30 => ['Interior Designer', 'Delete current IDP', '30_DeleteCurrentIDPPage', '30_DeleteCurrentIDPController', 'IDP'],
    31 => ['Interior Designer', 'Search current IDP', '31_SearchCurrentIDPPage', '31_SearchCurrentIDPController', 'IDP'],
    32 => ['Interior Designer', 'Add past IDP to portfolio', '32_AddPastIDPPage', '32_AddPastIDPController', 'IDP'],
    33 => ['Interior Designer', 'View past IDP', '33_ViewPastIDPPage', '33_ViewPastIDPController', 'IDP'],
    34 => ['Interior Designer', 'Update past IDP', '34_UpdatePastIDPPage', '34_UpdatePastIDPController', 'IDP'],
    35 => ['Interior Designer', 'Delete past IDP', '35_DeletePastIDPPage', '35_DeletePastIDPController', 'IDP'],
    36 => ['Interior Designer', 'Search past IDP', '36_SearchPastIDPPage', '36_SearchPastIDPController', 'IDP'],

    37 => ['Interior Designer', 'View project statistics (views)', '37_ProjectAnalyticsPage', '37_ProjectAnalyticsController', 'IDP'],
    38 => ['Interior Designer', 'Track project shortlists', '38_IDPAnalyticsPage', '38_IDPAnalyticsController', 'IDP'],

    39 => ['Platform Manager', 'Login', '13_20_39_LoginPage', '13_20_39_LoginController', 'UserAccount, UserProfile'],
    40 => ['Platform Manager', 'Logout', '14_21_40_LogOutPage', '14_21_40_LogOutController', 'UserSession'],
    41 => ['Platform Manager', 'Create IDP category', '41_CreateCategoryPage', '41_CreateCategoryController', 'Category'],
    42 => ['Platform Manager', 'View IDP category', '42_ViewCategoryPage', '42_ViewCategoryController', 'Category'],
    43 => ['Platform Manager', 'Update IDP category', '43_UpdateCategoryPage', '43_UpdateCategoryController', 'Category'],
    44 => ['Platform Manager', 'Delete IDP category', '44_DeleteCategoryPage', '44_DeleteCategoryController', 'Category'],
    45 => ['Platform Manager', 'Search IDP categories', '45_SearchCategoryPage', '45_SearchCategoryController', 'Category'],
    46 => ['Platform Manager', 'Generate daily report', '46_DailyReportPage', '46_DailyReportController', 'Report'],
    47 => ['Platform Manager', 'Generate weekly report', '47_WeeklyReportPage', '47_WeeklyReportController', 'WeeklyReport'],
    48 => ['Platform Manager', 'Generate monthly report', '48_MonthlyReportPage', '48_MonthlyReportController', 'MonthlyReport'],
];

const NEEDS_SELECTION = [23, 24, 29, 30, 34, 35, 42, 43, 44];

function storyFile(int $number, string $name): string
{
    return $name . '.php';
}
