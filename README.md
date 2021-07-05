# PHP-Login-System
Simple PHP login page with SQL injection prevention, a MySQL database integration, and password encryption.

# How to use
* Modify the inc.db.php file with the credentials of your MySQL database.
* Modify the $successfulLogin variable in index.php to contain the filepath for the page you want to send your user to after a succeessful login.
* When you want the user to be logged out, send them to logOut.php and it will end the user's session.
