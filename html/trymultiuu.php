<?php 
$db = mysqli_connect('localhost','root','','try'); 

$ingredients = implode(',',$_POST['ingredients']);
echo $ingredients; //you could comment this
$sql = "INSERT INTO `trymulti` (`ingredients`) VALUES ('".$ingredients."')"; 
	$result=mysqli_query($db,$sql);
								if($result == 1) 
								{
					
									header('Location: profiling.php');
								}
								else 
								{
									echo "<p>Sira!</p>";
								}
?>