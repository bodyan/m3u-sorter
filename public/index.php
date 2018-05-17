<?php

require_once 'src/Core/Model.php';
require_once 'src/Controller/CustomFunc.php';

$db = new Model();

$user = getUser($db,1);

if(!empty($user)) $user = $user[0]['rowid'];

$userLists = getUserLists($db, $user);

    if (isset($_POST["submit"]) && !empty($_POST) && isset($_FILES) && !empty($_FILES)) {

        if (isPlaylist($_FILES)) {
            $list = parsePlaylist($_FILES["fileToUpload"]["tmp_name"]);
            if(count($list) == 0) {
                header('Location:/');
                exit;
            }
            $name = filter_var($_POST['playlist-name'], FILTER_SANITIZE_SPECIAL_CHARS);
            $link = generateRandomString();
            insertUserList($db, 1, $list, $name, $link);
            header('Location:/');
        } else {   
            header('Location:/');
        }

    } else {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8" />
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <title>Page Title</title>
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <!-- Latest compiled and minified CSS -->
            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
            <!-- Optional theme -->
            <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
            <!-- Latest compiled and minified JavaScript -->
            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
        </head>
        <body style="width: 80%; margin: 2em auto;">
            <div class="row">
                <?php foreach ($userLists as $value) : ?>
                    <a href="<?php echo $value['rowid']?>" class="btn btn-success" role="button">
                        <?php echo $value['name']?>
                    </a>
                <?php endforeach; ?>
            </div>
            <div class="row" style="margin-top:2em;">
                <form action="" method="post" enctype="multipart/form-data">
                <div class="form-group" style="padding:10px; border: #666 1px solid; border-radius: 5px; ">
                    <input type="text" name="playlist-name" class="form-control form-control-lg" placeholder="Please input a name of Playlist">
                    <label for="fileToUpload">Please select your playlist:</label>
                    <input type="file" name="fileToUpload" id="fileToUpload" class="form-control-file">
                    <br>
                    <button type="submit" class="btn btn-primary" value="Selet .m3u file" name="submit">Submit</button>
                </div>
                </form>
            </div>
        <?php
    }

?>






