<?php
if(isset($_POST["submit"])) {
  $target_dir = "img_uploads/";
  $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
  $uploadOk = 1;
  $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

  // Check if image file is a actual image or fake image
  if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
      echo "File is an image - " . $check["mime"] . ".<br>";
      $uploadOk = 1;
    } else {
      echo "File is not an image.<br>";
      $uploadOk = 0;
    }
  }

  // Check if file already exists
  if (file_exists($target_file)) {
    echo "Sorry, file already exists.<br>";
    $uploadOk = 0;
  }

  // Check file size
  if ($_FILES["fileToUpload"]["size"] > 500000) {
    echo "Sorry, your file is too large.<br>";
    $uploadOk = 0;
  }

  // Allow certain file formats
  if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
  && $imageFileType != "gif" ) {
    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.<br>";
    $uploadOk = 0;
  }

  // Check if $uploadOk is set to 0 by an error
  if ($uploadOk == 0) {
    echo("Sorry, your file was not uploaded.<br>");

  // if everything is ok, try to upload file
  } else{
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
      echo "The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded.<br>";
      // Database insert news beiträge
      include 'db_config.php';
      $file = $_FILES["fileToUpload"]["name"];
      $date = date('Y-m-d h:i', time());
      $title = $_POST["title"];
      $stmt = $mysqli->prepare("INSERT INTO news (n_path, n_date, n_title) VALUES (?, ?, ?)");
      $stmt->bind_param("sss", $file, $date, $title);
      $stmt->execute();
      $stmt->close();

      $_SESSION['message'] = "Upload successful";
      header("Location: index.php");
    } else {
      echo "Sorry, there was an error uploading your file.<br>";
    }
  }
}
?>
