<?php  
 include("config.php");
 if(isset($_POST["query"]))  
 {  
      $output = '';
      if($_POST["query"] != '')
      {
        $query = "SELECT * FROM r_stud_profile WHERE STUD_NO LIKE '%".$_POST["query"]."%'";  
        $result = mysqli_query($db, $query);  
        $output = '<ul class="list-unstyled">';  
        if(mysqli_num_rows($result) > 0)  
        {  
             while($row = mysqli_fetch_array($result))  
             {  
                  $output .= '<li class="search-item-name">'.$row["STUD_FNAME"].' '.$row["STUD_MNAME"].' '.$row["STUD_LNAME"].'<small class="hide student-no-hide">'.$row["STUD_NO"].'</small></li>';  
             }  
        }  
        else  
        {  
             $output .= '<li>No Existing Name</li>';  
        }  
        $output .= '</ul>';
      }
      else
      {
        $output = '<ul class="list-unstyled">';
        $output .= '<li>No Existing Name</li>';
        $output .= '</ul>';
      }
        
      echo $output;  
      }
  

?>
