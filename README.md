# CSIT314-CK

CK interior design platform: matches renovation customers to interior designers.
All 48 user stories follow the Boundary-Control-Entity (B-C-E) framework:
one Boundary page and one Control class per story, using shared Entity classes.

## Folder structure
| Folder | Contents |
|---|---|
| `Boundary/` | Pages the user sees: one per user story, named `<number>_<Name>Page.php` (e.g. `32_AddPastIDPPage.php`), plus `home.html`, `13_20_39_LoginPage.php` (one login form for every role), `Dashboard.php`, `StoryIndex.php` and shared `layout.php` |
| `Control/` | One controller per user story, named `<number>_<Name>Controller.php` (e.g. `32_AddPastIDPController.php`); shared base classes have no number |
| `Entity/` | Shared by several stories, so not numbered. Classes that hold data and talk to the database: `UserAccount`, `UserProfile`, `UserSession`, `Company`, `IDP`, `Shortlist`, `Category`, `Report`, `WeeklyReport`, `MonthlyReport` |
| `Database/` | `interior_design.sql` (schema + demo data) and `Database.php` (connection) |
| `Assets/` | Images: home page photos, `projects/` sample project photos, `uploads/` for designer uploads |
| `Tests/` | PHPUnit unit tests for every story (run automatically by GitHub Actions) |
| `.github/workflows/` | CI pipeline |

## Run locally (XAMPP)
1. Copy this folder into `C:\xampp\htdocs\`.
2. Start **Apache** and **MySQL** in XAMPP.
3. In phpMyAdmin, create an empty database `interior_design` and import `Database/interior_design.sql`.
   (If an old version exists, drop the database first, then create and import again.)
4. For the full test data (100+ records per data type), also import `Database/test_data.sql` **after** step 3.
5. Open `http://localhost/<folder name>/` and click **Login**.

## Test data
`Database/generate_data.php` creates `Database/test_data.sql` with random but realistic records:
210 users (accounts + profiles), 94 extra categories (100 in total), 100 companies, 150 projects,
200 shortlists and 600 project views spread over the last 30 days.

To regenerate it: `C:\xampp\php\php.exe Database\generate_data.php`, then re-import both SQL files into an empty database.

Every generated user's password is **Test123!**
| Role | Usernames |
|---|---|
| Customer | cust001 - cust100 |
| Interior Designer | des001 - des100 |
| Platform Manager | pm001 - pm005 |
| User Admin | ua001 - ua005 |

About 5% of generated users are suspended on purpose, so some of them cannot log in.

## Demo logins
| Role | Username | Password |
|---|---|---|
| User Admin | admin / manager | admin123 / Manager123! |
| Interior Designer | designer / designer2 | Designer123! / Designer234! |
| Customer | customer / customer2 | Customer123! / Customer234! |
| Platform Manager | platform | Platform123! |

## User story map
Also viewable in the app at `Boundary/StoryIndex.php`.

| # | Role | Story | Boundary | Control | Entity |
|---|---|---|---|---|---|
| 1 | User Admin | Login | `01_UserLoginPage.php` | `01_UserLoginController.php` | UserAccount, UserProfile |
| 2 | User Admin | Logout | `02_UserLogOutPage.php` | `02_UserLogOutController.php` | UserSession |
| 3 | User Admin | Create user profile | `03_CreateProfilePage.php` | `03_CreateProfileController.php` | UserProfile |
| 4 | User Admin | View user profile | `04_ViewProfilePage.php` | `04_ViewProfileController.php` | UserProfile |
| 5 | User Admin | Update user profile | `05_UpdateProfilePage.php` | `05_UpdateProfileController.php` | UserProfile |
| 6 | User Admin | Suspend user profile | `06_SuspendProfilePage.php` | `06_SuspendProfileController.php` | UserProfile |
| 7 | User Admin | Search user profiles | `07_SearchProfilePage.php` | `07_SearchProfileController.php` | UserProfile |
| 8 | User Admin | Create user account | `08_CreateAccountPage.php` | `08_CreateAccountController.php` | UserAccount |
| 9 | User Admin | View user account | `09_ViewAccountPage.php` | `09_ViewAccountController.php` | UserAccount |
| 10 | User Admin | Update user account | `10_UpdateAccountPage.php` | `10_UpdateAccountController.php` | UserAccount |
| 11 | User Admin | Suspend user account | `11_SuspendAccountPage.php` | `11_SuspendAccountController.php` | UserAccount |
| 12 | User Admin | Search user accounts | `12_SearchAccountPage.php` | `12_SearchAccountController.php` | UserAccount |
| 13 | Interior Designer | Login | `13_20_39_LoginPage.php` | `13_20_39_LoginController.php` | UserAccount, UserProfile |
| 14 | Interior Designer | Logout | `14_21_40_LogOutPage.php` | `14_21_40_LogOutController.php` | UserSession |
| 15 | Interior Designer | Register company information | `15_RegisterCompanyPage.php` | `15_RegisterCompanyController.php` | Company |
| 16 | Interior Designer | View company information | `16_ViewCompanyPage.php` | `16_ViewCompanyController.php` | Company |
| 17 | Interior Designer | Update company information | `17_UpdateCompanyPage.php` | `17_UpdateCompanyController.php` | Company |
| 18 | Interior Designer | Delete company information | `18_DeleteCompanyPage.php` | `18_DeleteCompanyController.php` | Company |
| 19 | Interior Designer | Search company information | `19_SearchCompanyPage.php` | `19_SearchCompanyController.php` | Company |
| 20 | Customer | Login | `13_20_39_LoginPage.php` | `13_20_39_LoginController.php` | UserAccount, UserProfile |
| 21 | Customer | Logout | `14_21_40_LogOutPage.php` | `14_21_40_LogOutController.php` | UserSession |
| 22 | Customer | Search IDP by keyword and category | `22_SearchIDPPage.php` | `22_SearchIDPController.php` | IDP |
| 23 | Customer | View IDP | `23_ViewIDPPage.php` | `23_ViewIDPController.php` | IDP |
| 24 | Customer | Save IDP to shortlist | `24_SaveShortlistPage.php` | `24_SaveShortlistController.php` | Shortlist, IDP |
| 25 | Customer | Search and filter shortlist | `25_ShortlistSearchPage.php` | `25_ShortlistSearchController.php` | Shortlist |
| 26 | Customer | View shortlisted IDP side by side | `26_ViewShortlistPage.php` | `26_ViewShortlistController.php` | Shortlist |
| 27 | Interior Designer | Create current IDP | `27_CreateCurrentIDPPage.php` | `27_CreateCurrentIDPController.php` | IDP |
| 28 | Interior Designer | View current IDP | `28_ViewCurrentIDPPage.php` | `28_ViewCurrentIDPController.php` | IDP |
| 29 | Interior Designer | Update current IDP | `29_UpdateCurrentIDPPage.php` | `29_UpdateCurrentIDPController.php` | IDP |
| 30 | Interior Designer | Delete current IDP | `30_DeleteCurrentIDPPage.php` | `30_DeleteCurrentIDPController.php` | IDP |
| 31 | Interior Designer | Search current IDP | `31_SearchCurrentIDPPage.php` | `31_SearchCurrentIDPController.php` | IDP |
| 32 | Interior Designer | Add past IDP to portfolio | `32_AddPastIDPPage.php` | `32_AddPastIDPController.php` | IDP |
| 33 | Interior Designer | View past IDP | `33_ViewPastIDPPage.php` | `33_ViewPastIDPController.php` | IDP |
| 34 | Interior Designer | Update past IDP | `34_UpdatePastIDPPage.php` | `34_UpdatePastIDPController.php` | IDP |
| 35 | Interior Designer | Delete past IDP | `35_DeletePastIDPPage.php` | `35_DeletePastIDPController.php` | IDP |
| 36 | Interior Designer | Search past IDP | `36_SearchPastIDPPage.php` | `36_SearchPastIDPController.php` | IDP |
| 37 | Interior Designer | View project statistics (views) | `37_ProjectAnalyticsPage.php` | `37_ProjectAnalyticsController.php` | IDP |
| 38 | Interior Designer | Track project shortlists | `38_IDPAnalyticsPage.php` | `38_IDPAnalyticsController.php` | IDP |
| 39 | Platform Manager | Login | `13_20_39_LoginPage.php` | `13_20_39_LoginController.php` | UserAccount, UserProfile |
| 40 | Platform Manager | Logout | `14_21_40_LogOutPage.php` | `14_21_40_LogOutController.php` | UserSession |
| 41 | Platform Manager | Create IDP category | `41_CreateCategoryPage.php` | `41_CreateCategoryController.php` | Category |
| 42 | Platform Manager | View IDP category | `42_ViewCategoryPage.php` | `42_ViewCategoryController.php` | Category |
| 43 | Platform Manager | Update IDP category | `43_UpdateCategoryPage.php` | `43_UpdateCategoryController.php` | Category |
| 44 | Platform Manager | Delete IDP category | `44_DeleteCategoryPage.php` | `44_DeleteCategoryController.php` | Category |
| 45 | Platform Manager | Search IDP categories | `45_SearchCategoryPage.php` | `45_SearchCategoryController.php` | Category |
| 46 | Platform Manager | Generate daily report | `46_DailyReportPage.php` | `46_DailyReportController.php` | Report |
| 47 | Platform Manager | Generate weekly report | `47_WeeklyReportPage.php` | `47_WeeklyReportController.php` | WeeklyReport |
| 48 | Platform Manager | Generate monthly report | `48_MonthlyReportPage.php` | `48_MonthlyReportController.php` | MonthlyReport |

Stories #13, #20 and #39 share one login page and controller (`13_20_39_LoginPage.php` → `13_20_39_LoginController.php`), and #14, #21 and #40 share one logout page and controller (`14_21_40_LogOutPage.php` → `14_21_40_LogOutController.php`), matching the design diagrams. #1 and #2 extend them for the User Admin.
`IDPController.php` is a shared base class for #27-#36 (validation and photo upload).

## Run tests
```
phpunit Tests
```
