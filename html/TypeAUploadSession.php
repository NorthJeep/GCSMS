<?php
    $con = mysqli_connect("localhost","root","","g&csms_db");
    if (isset($_POST['save'])) {
    $target_dir = "Files/";
    $target_file = $target_dir . basename($_FILES["file"]["name"]);
    $uploadOk = 1;
    $FileType = pathinfo($target_file,PATHINFO_EXTENSION);
     
    if($FileType != "docx" || $FileType != "pdf" || $FileType != "doc" || $FileType != "xls" || $FileType != "xlsx") {
    if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
    $files = basename($_FILES["file"]["name"]);
    }else{
    echo "Error Uploading File";
    exit;
    }
    }else{
    echo "File Not Supported";
    exit;
    }
    $filename = $_POST['T_UPLOAD_NAME'];
    $location = "Files/" . $files;
    $sqli = "INSERT INTO T_UPLOAD (T_UPLOAD_NAME,T_UPLOAD_CATEGORY,T_UPLOAD_DATE,T_UPLOAD_LOCATION) VALUES ('{$filename}','".$_POST['T_UPLOAD_CATEGORY']."',CURDATE(),'{$location}')";
    $result = mysqli_query($con,$sqli);
    if ($result) {
        header('Location: TypeAFilesAndDocuments.php' . $redirect);
    };
}
?>