<?php
session_start();
if (isset($_GET['logout'])) { // on logout delete all set authentication values
    session_start();
    unset($_SESSION['username']);
    unset($_SESSION['password']);
    unset($_SESSION['logged_in']);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web File Browser</title>
    <link rel="stylesheet" href="assets/css/reset.css">
    <link rel="stylesheet" href="assets/css/main.min.css">
</head>

<body>
    <?php // Authentication/authorization section to allow access to file browser
    $msg = '';
    if(isset($_POST['login']) && !empty($_POST['username']) && !empty($_POST['password'])) {
        if($_POST['username'] == 'admin' && $_POST['password'] == 'letmein') {
            $_SESSION['logged_in'] = true;
            $_SESSION['timeout'] = time();
            $_SESSION['username'] = 'admin';
        } else {
            $msg = '<strong>Authorization failure:</strong> wrong username or password!';
        }
    }
    ?>

    <?php 
        if($_SESSION['logged_in'] == false) {
            echo('<div class="login-wrap">
                    <div class="heading-wrap">
                        <h2>Log In</h2>
                        <div class="tip">
                            <span>?</span>
                            <p>Use the following credentials to log in:<br><br>
                                Username: admin<br>
                                Password: letmein<br><br>
                                You\'re welcome!
                            </p>
                        </div>
                    </div>
                    <span class="warn-msg">' . $msg . '</span>
                    <form action = "" method = "post">
                        <label for="username">Enter your username:<br></label>
                        <input type="text" id="username" name="username" required autofocus></br>
                        <label for="password">Enter your password:<br></label>
                        <input type="password" id="password" name="password" required><br>
                        <button class="login-btn btn" type="submit" name="login">Login</button>
                    </form>
                </div>');
            die();
        }
    ?>

    <h1>Tiny File Browser <span>(100% PHP guaranteed)</span></h1>

    <form class="logout" action="./index.php" method="GET"> <!-- Logout button -->
        <button class="logout-btn btn" type="submit" name="logout">Log Out</button>
    </form>
    
    <?php
    $path = "." . $_GET['path'];

    if (isset($_POST['name']) && $_POST['name'] != '') { // creates a folder of user-specified 'name' in current working directory
        mkdir($path . "/" . ($_POST['name']));
    }

    if(isset($_POST['upload'])) { // file upload
        $file_name = $_FILES['file']['name'];
        $file_size = $_FILES['file']['size'];
        $file_tmp = $_FILES['file']['tmp_name'];
        $file_type = $_FILES['file']['type'];
        $file_store = ($path . "/") . $file_name; 
        move_uploaded_file($file_tmp, $file_store);
    }

    if (array_key_exists('action', $_GET)) { // file actions: delete and download
        if (array_key_exists('file', $_GET)) {
                $file = "./" . $_GET['path'] . "./" . $_GET['file'];
            if ($_GET['action'] == 'delete') {
                unlink($path . "/" . $_GET['file']);
            } elseif ($_GET['action'] == 'download') {
                $fileDL = str_replace("&nbsp;", " ", htmlentities($file, null, 'utf-8'));
                ob_clean();
                ob_flush();
                header('Content-Description: File Transfer');
                header('Content-Type: application/pdf');
                header('Content-Disposition: attachment; filename=' . basename($fileDL));
                header('Content-Transfer-Encoding: binary');
                header('Expires: 0');
                header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                header('Pragma: public');
                header('Content-Length: ' . filesize($fileDL));
                ob_end_flush();
                readfile($fileDL);
                exit;
            }
        }
    }

    $dir_contents = scandir($path);


    echo '<div class="path-wrap">Current&nbsp;directory:<span>' . $path . '/</span></div>';

    $split = explode("/", $_GET['path']);
    $emptyString = "";
    for ($i = 0; $i < count($split) - 1; $i++) {
        if ($split[$i] == "")
            continue;
        $emptyString .= "/" . $split[$i];
    }
    echo ("<button class=\"back-btn\">" . "<a href='./?path=" . $emptyString . "'>" . "Back" . "</a>" . "</button>");

    // Table forming start
    echo ("<table>
            <thead>
                <tr>
                    <th>Type</th>
                    <th>Name</th>
                    <th>Actions</th>
                </tr>
            </thead>");

    echo ("<tbody>");

    foreach ($dir_contents as $item) {
        if ($item == "." || $item == "..") {
            continue;
        }
        echo ("<tr><td>" . (is_dir($path . "/" . $item) ? "<span class=\"fold\">Folder</span>" : "<span class=\"file\">File</span>") . "</td>");
        if (is_dir($path . "/" . $item)) {
            echo ("<td>" . "<a href='./?path=" . $_GET['path'] . "/" . $item . "'>" . $item .  "</a></td>");
        } else {
            echo ("<td>" . $item . "</td>");
        }
        if (is_file($path . "/" . $item)) {
            if ($item != "index.php") {
                echo ("<td><button class=\"delete-btn action-btn\"><a href='./?path=" . $_GET['path'] . "&file=" . $item . "&action=delete" . "'>" . "Delete</a></button><button class=\"download-btn action-btn\"><a href='./?path=" . $_GET['path'] . "&file=" . $item . "&action=download" . "'>" . "Download</a></button></td>");
            } else {
                echo ("<td></td>");
            }
        } else {
            echo ("<td></td>");
        }
    }
    echo ("</tbody></table>"); // Table forming end
    ?>

    <div class="new-content">
        <form class="create-folder" action="<?php $path ?>" method="POST">
            <label for="name">Create new folder:<br></label>
            <input type="text" id="name" name="name" placeholder="Folder name">
            <button type="submit">+</button>
            <br>
        </form>

        <form class="upload" action="" method="POST" enctype="multipart/form-data">
            <label>Upload a file:<br></label>
            <input type="file" name="file">
            <button type="submit" name="upload">Upload file here</button>
        </form>
    </div>
    
</body>

</html>