<?php  
 include("config.php");
 if(isset($_POST["query"]))  
 {  
      $output = '';  
      $query = "SELECT * FROM r_stud_profile WHERE STUD_FNAME LIKE '%".$_POST["query"]."%'";  
      $result = mysqli_query($db, $query);  
      $output = '<ul class="list-unstyled">';  
      if(mysqli_num_rows($result) > 0)  
      {  
           while($row = mysqli_fetch_array($result))  
           {  
                $output .= '<li>'.$row["STUD_NO"].'</li>';  
           }  
      }  
      else  
      {  
           $output .= '<li>Country Not Found</li>';  
      }  
      $output .= '</ul>';  
      echo $output;  
 } 

?>
