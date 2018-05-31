<?php  
 include("config.php");
 if(isset($_POST["query"]))  
 {  
      $output = '';
      if($_POST["query"] != '')
      {
        $query = "SELECT * FROM r_stud_profile WHERE Stud_FNAME LIKE '%".$_POST["query"]."%'";  
        $result = mysqli_query($db, $query);  
        $output = '<ul class="list-unstyled">';  
        if(mysqli_num_rows($result) > 0)  
        {  
             while($row = mysqli_fetch_array($result))  
             {  
                  $output .= '<li class="search-item-no">'.$row["Stud_NO"].' <small class="hide student-name-hide">'.$row["Stud_FNAME"].' '.$row["Stud_MNAME"].' '.$row["Stud_LNAME"].'</small></li>';  
             }  
        }  
        else  
        {  
             $output .= '<li>No Existing Student Number</li>';  
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
