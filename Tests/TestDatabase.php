<?php
class TestDatabase
{
    public static function create(): PDO
    {
        $db = new PDO('sqlite::memory:', null, null, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
        $db->exec('CREATE TABLE useraccount (userId INTEGER PRIMARY KEY AUTOINCREMENT, username TEXT UNIQUE NOT NULL,
                   password TEXT NOT NULL, email TEXT NOT NULL, status TEXT NOT NULL)');
        $db->exec("CREATE TABLE userprofile (username TEXT UNIQUE NOT NULL, password TEXT NOT NULL, name TEXT NOT NULL,
                   type TEXT NOT NULL, status TEXT NOT NULL DEFAULT 'Active')");
        $db->exec('CREATE TABLE category (categoryID INTEGER PRIMARY KEY AUTOINCREMENT, categoryName TEXT NOT NULL, description TEXT NOT NULL)');
        $db->exec('CREATE TABLE company (companyID INTEGER PRIMARY KEY AUTOINCREMENT, companyName TEXT NOT NULL, regNum TEXT NOT NULL,
                   description TEXT NOT NULL, address TEXT NOT NULL, designerID INTEGER NOT NULL UNIQUE)');
        $db->exec("CREATE TABLE idp (projectID INTEGER PRIMARY KEY AUTOINCREMENT, title TEXT NOT NULL, category TEXT NOT NULL,
                   description TEXT NOT NULL, photos BLOB NOT NULL, designerID INTEGER NOT NULL, status TEXT NOT NULL,
                   progress INTEGER NOT NULL, stage TEXT NOT NULL DEFAULT 'Current', createdAt TEXT NOT NULL, updatedAt TEXT NOT NULL)");
        $db->exec('CREATE TABLE shortlist (shortlistID INTEGER PRIMARY KEY AUTOINCREMENT, customerID INTEGER NOT NULL,
                   projectID INTEGER NOT NULL, createdAt TEXT NOT NULL, UNIQUE (customerID, projectID))');
        $db->exec('CREATE TABLE project_views (viewID INTEGER PRIMARY KEY AUTOINCREMENT, projectID INTEGER NOT NULL, viewedAt TEXT NOT NULL)');
        $db->exec('CREATE TABLE report (reportID INTEGER PRIMARY KEY AUTOINCREMENT, reportType TEXT NOT NULL,
                   generationDate TEXT NOT NULL, summaryData TEXT NOT NULL)');
        return $db;
    }

    public static function addUser(PDO $db, string $username, string $password, string $name, string $type): int
    {
        (new UserAccount($db))->createAccount($username, $password, $username . '@test.com');
        (new UserProfile($db))->saveProfile($username, $password, $name, $type);
        return (new UserAccount($db))->searchAccount($username)->getUserId();
    }
}
