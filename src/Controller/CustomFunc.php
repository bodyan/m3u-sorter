<?php 
function getUser($db, int $id = null):array
{
    if($id === null) return [];
    $query = $db->connection
        ->prepare("SELECT
                    rowid, username, password, email
                    FROM users
                    WHERE rowid = :id"
        );
    $query->bindValue(':id', $id, PDO::PARAM_INT);
    $query->execute();
    $query->setFetchMode(PDO::FETCH_ASSOC);
    $result = $query->fetchAll();
    return $result;
    
}

function getUserLists($db, int $user = null):array
{
    if($user === null) return [];
    $query = $db->connection
        ->prepare("SELECT
                    rowid, id_user, data, name
                    FROM playlists
                    WHERE id_user = :user"
        );
    $query->bindValue(':user', $user, PDO::PARAM_INT);
    $query->execute();
    $query->setFetchMode(PDO::FETCH_ASSOC);
    $result = $query->fetchAll();
    return $result;
}

function insertUserList($db, int $user = null, array $data = null, $name = null, $url = null ):bool
{
    if($user === null || $data === null) return false;
    $data = json_encode( $data );
    $query = $db->connection->prepare("INSERT INTO playlists (id_user, data, name, url) VALUES (? ,? ,?, ?)");
    $query->bindParam(1, $user);
    $query->bindParam(2, $data);
    $query->bindParam(3, $name);
    $query->bindParam(4, $url);

    $query->execute();
    return true;
}
function isPlaylist($playlist):bool 
{
    $uploadOk = 1;
    //check size
    if ($_FILES["fileToUpload"]["size"] > 500000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    $playlistFileType = pathinfo($_FILES["fileToUpload"]["name"], PATHINFO_EXTENSION);
    if($playlistFileType != "m3u" ) {
        echo "Sorry, M3U files are allowed.";
        $uploadOk = 0;
    }

    return ($uploadOk == 0) ? false : true;

}

function parsePlaylist($playlist):array
{   
    if (!file_exists($playlist)) {
    	exit("File $playlist not exist!");
    }
    
    $handle = fopen($playlist, "r");
    $list = [];
    if ($handle) {
        while(!feof($handle)) {
            $service = trim(fgets($handle));
            $pos = strpos($service, '#EXTINF');
            if($pos === false ) continue;
            $link = trim(fgets($handle));
            $link = filter_var($link, FILTER_SANITIZE_SPECIAL_CHARS);
            $service = trim(substr(strstr($service, ',', false), 1));
            $service = filter_var($service, FILTER_SANITIZE_SPECIAL_CHARS);
            $list[$service] = $link;
        }
    fclose($handle);
    }
    return $list;
}


function generateRandomString($length = 10) {

    //https://stackoverflow.com/a/4356295/4359099

    $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}