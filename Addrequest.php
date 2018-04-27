<?php

    $user2 = 'root';
    $pass2 = '';
    $dbnm2 = 'g&csms_db';

    try 
    {
        $dbh2 = new PDO('mysql:host=localhost;dbname='.$dbnm2, $user2, $pass2);
    } 
    catch (PDOException $e) 
    {
        print "Error!: " . $e->getMessage() . "<br/>";
        die();
    }

    $stmt2 = $dbh2->prepare("INSERT INTO ims_t_summary_request(Date_Requested, Status_Req, Remarks) VALUES (?, 'PENDING', ?)");
    $stmt2->bindParam(1, $getdate);
    $stmt2->bindParam(2, $rmrks);

    $arr2 = $_POST;
    $getdate = $arr2['currentdate'];
    $rmrks = $arr2['r_remarks'];
    $stmt2->execute();

    echo $getdate;
    echo $rmrks;
    echo "<br>";

    $dbh2 = null;

?>


<?php  

    $user = 'root';
    $pass = '';
    $dbnm = 'pupqcims_db';

    try 
    {
        $dbh = new PDO('mysql:host=localhost;dbname='.$dbnm, $user, $pass);
    } 
    catch (PDOException $e) 
    {
        print "Error!: " . $e->getMessage() . "<br/>";
        die();
    }

    $stmt = $dbh->prepare("INSERT INTO ims_t_requisition(Item_Name, Unit, Quantity, Batch_No) VALUES (?, ?, ?, ?)");
    $stmt->bindParam(1, $name);
    $stmt->bindParam(2, $unit);
    $stmt->bindParam(3, $qty);
    $stmt->bindParam(4, $ursid);


    $arr = $_POST; 
    for($i = 0; $i <= count($arr['r_name'])-1;$i++)
    {
        $name = $arr['r_name'][$i];
        $unit = $arr['r_unit'][$i];
        $qty = $arr['r_quantity'][$i]; 
        $ursid = $arr['r_batch'];  
        $stmt->execute();

        echo $name;
        echo $unit;
        echo $qty;
        echo $ursid;    
        echo "<br>";
    }

     header('Location: PCPenReq.php');

?>