# TINY FILE BROWSER (PHP)
## TASK:
Create a PHP-based file browser to **display** contents & allow different **interactions** with the contents of the folder the `index.php` code is executed in.
## FEATURES:
The File Browser comes with user authentication via username and password log in form. Once logged in, browser presents the user with following functionalities:
- File browsing (*NOTE: Moving up from the initial working directory is prohibitted*)
- File deletion
- File download
- File upload
- Folder creation
## HOW IT WORKS:
1) To start using the File Browser, the user needs to Log In. To do so, a valid username-password need to be provided.

At the current state both, the username and the password are hardcoded into the `index.php` file as follows:
```
if($_POST['username'] == 'admin' && $_POST['password'] == 'letmein') {
            $_SESSION['logged_in'] = true;
            $_SESSION['timeout'] = time();
            $_SESSION['username'] = 'admin';
        } else {
            $msg = '<strong>Authorization failure:</strong> wrong username or password!';
        }
```
To make sure the user is able to log in, a tooltip with the correct username and password can be found on the Log In form page.

2) Once logged in the user is free to explore the file browser and its functionalities.

#### Folder interactions:

3) Click on folder names to browse through selected folders.

4) Click the `Back` button located above the table at any time to move back to the previous folder.

5) Create a new folder at the current working directory by entering the new folder's name and clicking `+` in the input form bellow the table.

#### File interactions:

4) Click on `Delete` button next to each file's name to delete it ( *Disabled for the index.php file* ).

4) Click on `Download` button next to each file's name to download it to your selected location on your computer ( *Disabled for the index.php file* ).

5) Upload a new file to the current working directory by choosing a file from your computer and clicking `Upload file here` in the input form bellow the table.

## POSSIBLE IMPROVEMENTS:
- **Registration form.** To allow registration of new users.
- **Delete empty folders.** If not empty - request for additional confirmation.