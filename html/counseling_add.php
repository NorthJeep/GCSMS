<?php
    include('config.php');

    $student_no = $_POST["student_no"];
    $student_name = $_POST["student_name"];
    
        $sql = "INSERT INTO t_counseling_service (student_no,student_name) 
                            VALUES ('$student_no','$student_name')";
        mysqli_query($db,$sql);

?>