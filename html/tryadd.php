<?php
                        $db = mysqli_connect('localhost','root','','try');

                        $type=$_POST['type'];

                        $sql="INSERT INTO type (COUNSELING_TYPE_NAME) VALUES('$type')";

                        $result=mysqli_query($db,$sql);
                                if($result == 1) 
                                {
                                    echo "<p>WOW!<p>";
                                }
                                else 
                                {
                                    echo "<p>Sira!</p>";
                                }

                        ?>