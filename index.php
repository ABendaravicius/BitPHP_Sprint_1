<!-- Authentication/authorization section to allow access to file browser -->
<?php
session_start();
if (isset($_GET['logout'])) {
    session_start();
    unset($_SESSION['username']);
    unset($_SESSION['password']);
    unset($_SESSION['logged_in']);
    // session_destroy();
}

if (isset($_POST['login']) && !empty($_POST['username']) && !empty($_POST['password'])) {
    if ($_POST['username'] == 'admin' && $_POST['password'] == '1337c0d3') {
        $_SESSION['logged_in'] = true;
        $_SESSION['username'] = 'admin';
    } else {
        $msg = 'Wrong username or password!';
    }
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
    <div>
        <?php 
        $msg = '';
        if(isset($_POST['login']) && !empty($_POST['username']) && !empty($_POST['password'])) {
            if($_POST['username'] == 'admin' && $_POST['password'] == '1337c0d3') {
                $_SESSION['logged_in'] = true;
                $_SESSION['timeout'] = time();
                $_SESSION['username'] = 'admin';
            } else {
                $msg = 'Wrong username or password!';
            }
        }
        ?>
    </div>
    <?php 
        if($_SESSION['logged_in'] == false) {
            echo('<form action = "" method = "post">' );
            echo('<h2>' . $msg . '</h2>');
            echo('<h3>Log In </h3>');
            echo('<input type = "text" name = "username" placeholder = "user" required autofocus></br>');
            echo('<input type = "password" name = "password" placeholder = "user1" required><br>');
            echo('<button class = "button" type = "submit" name = "login">Login</button>');
            echo('</form>');
            die();
        } 
        // else {
        //     echo('<button type="submit" name="logout">Logout</button>');
        // }
    ?>
    <h1>Web file browser</h1>
    <?php
    $path = "." . $_GET['path'];
    if (isset($_POST['name'])) {
        mkdir($path . "/" . ($_POST['name']));
    }
    if(isset($_POST['upload'])) {
        $file_name = $_FILES['file']['name'];
        $file_size = $_FILES['file']['size'];
        $file_tmp = $_FILES['file']['tmp_name'];
        $file_type = $_FILES['file']['type'];
        $file_store = ($path . "/") . $file_name;
        move_uploaded_file($file_tmp, $file_store);  
    }
    if (array_key_exists('action', $_GET)) {    
        if (array_key_exists('file', $_GET)) {
                $file = "./" . $_GET['path'] . "./" . $_GET['file'];
            if ($_GET['action'] == 'delete') {
                unlink($path . "/" . $_GET['file']);
            } elseif ($_GET['action'] == 'download') {
                $fileDown = str_replace("&nbsp;", " ", htmlentities($file, null, 'utf-8'));
                ob_clean();
                ob_flush();
                header('Content-Description: File Transfer');
                header('Content-Type: application/pdf');
                header('Content-Disposition: attachment; filename=' . basename($fileDown));
                header('Content-Transfer-Encoding: binary');
                header('Expires: 0');
                header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                header('Pragma: public');
                header('Content-Length: ' . filesize($fileDown));
                ob_end_flush();
                readfile($fileDown);
                exit;
            }
        }
    }
    $dir_contents = scandir($path);
    echo ("<table><thead><tr>
        <th>Type</th>
        <th>Name</th>
        <th>Actions</th>
        </tr></thead>");
    echo ("<tbody><tr>");
    foreach ($dir_contents as $cont) {
        if ($cont == "." || $cont == "..") {
            continue;
        }
        echo ("<tr><td>" . (is_dir($path . "/" . $cont) ? "Dir" : "File") . "</td>");
        if (is_dir($path . "/" . $cont)) {
            echo ("<td>" . "<a href='./?path=" . $_GET['path'] . "/" . $cont . "'>" . $cont .  "</a></td>");
        } else {
            echo ("<td>" . $cont . "</td>");
        }
        if (is_file($path . "/" . $cont)) {
            if ($cont != "index.php") {
                echo ("<td><button><a href='./?path=" . $_GET['path'] . "&file=" . $cont . "&action=delete" . "'>" . "Delete</a></button><button><a href='./?path=" . $_GET['path'] . "&file=" . $cont . "&action=download" . "'>" . "Download</a></button></td>");
            } else {
                echo ("<td></td>");
            }
        } else {
            echo ("<td></td>");
        }
    }
    echo ("</tbody></table>");
    $split = explode("/", $_GET['path']);
    $emptyString = "";
    for ($i = 0; $i < count($split) - 1; $i++) {
        if ($split[$i] == "")
            continue;
        $emptyString .= "/" . $split[$i];
    }
    echo ("<button>" . "<a href='./?path=" . $emptyString . "'>" . "BACK" . "</a>" . "</button>");
    ?>
    <form action="<?php $path ?>" method="POST">
        <label for="name">Directory name</label>
        <br>
        <input type="text" id="name" name="name" placeholder="Eneter dir name">
        <button type="submit">Create new directory</button>
        <br>
        <br>
        <br>
    </form>
    <form action="./index.php" method="GET">
        <button type="submit" name="logout">Logout</button>
    </form>
    <form action="" method="POST" enctype="multipart/form-data">
        <input type="file" name="file">
        <br>
        <button type="submit" name="upload">Upload file</button>
    </form>
</body>

</html>