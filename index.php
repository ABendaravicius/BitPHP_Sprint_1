<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP File Explorer</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>PHP File Explorer</h1>
    <?php
        print '<div class="dir-wrap"><h4>Current directory: </h4><span>' . getcwd() . $_GET['path'] . '</span></div>';
    ?>
    <table>
        <thead>
            <tr>
                <th>Type</th>
                <th>Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $dir = "." . $_GET['path']; // saving initial directory
                print $dir . "<br>";
                print $_GET['path'];
                $contents = scandir($dir); // scanning contents of initial directory
                
                print '<pre>';
                print_r($contents);
                print '</pre>';

                $contents = array_map(function ($item) {
                    $dir = "." . $_GET['path'];
                    return $item = $dir . "/" . $item;
                }, $contents);
                

                for ($i = 0; $i < count($contents); $i++) {
                    print '<tr>'; // table row start
                    
                    if (is_dir($contents[$i])) { 
                        print '<td>Folder</td>'; // fill first table data cell
                        print '<td><a href="./?path=' . $_GET['path'] . "/" . $contents[$i] . '"><span>' . $contents[$i] . '</span></a></td>'; // fill second table data cell
                    } elseif (is_file($contents[$i])) {
                        print '<td>File</td>'; // fill first table data cell
                        print '<td>' . $contents[$i] . '</td>'; // fill second table data cell
                    } 
                    // else {
                    //     print '<td>Unknown</td>'; // fill first table data cell
                    //     print '<td>' . $contents[$i] . '</td>'; // fill second table data cell
                    // }

                    print '<td><button class="del">Delete</button><button>Download</button></td>'; // fill third table data cell

                    print '</tr>'; // table row end
                }

                print '<br>';
            ?>
        </tbody>
    </table>
    
</body>
</html>