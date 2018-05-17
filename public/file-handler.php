
<?php
//$target_file = $_FILES["fileToUpload"]["tmp_name"];
$uploadOk = 1;
$playlistFileType = pathinfo($_FILES["fileToUpload"]["name"], PATHINFO_EXTENSION);
// Check if image file is a actual image or fake image
if(!isset($_POST["submit"])) {
	header("Location: http://localhost/iptv/");
}

print_r($_FILES);

// Check file size
if ($_FILES["fileToUpload"]["size"] > 500000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}
// Allow certain file formats
if($playlistFileType != "m3u" ) {
    echo "Sorry, M3U files are allowed.";
    $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
 	print_r(readM3U($_FILES["fileToUpload"]["tmp_name"]));
}


function readM3U ($filename)
{   
    if (!file_exists($filename)) {
    	exit("File $filename not exist!");
    }
    
    $handle = fopen($filename, "r");
    $list = [];
    if ($handle) {
        while(!feof($handle)) {
            $service = trim(fgets($handle));
            $pos = strpos($service, '#EXTINF');
            if($pos === false ) continue;
            $link = trim(fgets($handle));
            $service = trim(substr(strstr($service, ',', false), 1));
            $list[$service] = $link;
        }
    fclose($handle);
    }
    return $list;
}

?>
