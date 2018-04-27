<?php
require('fpdf.php');

class PDF extends FPDF
{


// Colored table
function Header()
{
    

    // Select Arial bold 15
    $this->SetFont('Arial','B',15);
    // Move to the right
    
    // Framed title
    
    $this->Image('images\PUPLogo.png',110,10,20);
    $this->Ln(30);
    $this->Cell(90);
    $this->Cell(30,10,'Polytechnic University of the Philippines',0,0,'C');
    // Line break
    $this->Ln(20);
}
// Colored table
function body()
{
    $this->Cell(90);
    $this->Cell(30,10,'Student Information',0,0,'C');
    $this->Ln(30);
    
   
    $fill = false;
   $db = mysqli_connect("localhost","root","","g&csms_db");

if (isset($_REQUEST['view']))
{
    
$id = $_REQUEST['view'];
$sql= mysqli_query($db, " SELECT `STUD_NO`,`STUD_NAME`,`COUNSELING_TYPE_CODE`,`COUNS_DATE`,`COUNS_BG`,`COUNS_GOALS`,`COUNS_COMMENTS`,`COUNS_RECOMM` FROM `t_counseling` where `COUNSELING_ID` ='$id' ");
while ($row = mysqli_fetch_array($sql))
{
        $this->Cell(9,9,'Student Number: '.$row[0].'',0,1,'L',$fill);
        $this->Cell(9,9,'Student Name: '.$row[1].'',0,1,'L',$fill);
        $this->Cell(9,9,'Counseling Type: '.$row[2].'',0,1,'L',$fill);
        $this->Cell(9,9,'Date: '.$row[3].'',0,1,'L',$fill);
        $this->Cell(9,9,'Counseling Background: '.$row[4].'',0,1,'L',$fill);
        $this->Ln(10);
        $this->Cell(9,9,'Counseling Goals: '.$row[5].'',0,1,'L',$fill);
        $this->Ln(10);
        $this->Cell(9,9,'Comments: '.$row[6].'',0,1,'L',$fill);
        $this->Ln(10);
        $this->Cell(9,9,'Recomendations: '.$row[7].'',0,1,'L',$fill);
        $this->Ln(10);
}
}
}
}

if (isset($_REQUEST['view']))
{

$db = mysqli_connect("localhost","root","","g&csms_db");   
$id = $_REQUEST['view'];
$sql= mysqli_query($db, "SELECT `STUD_NAME` FROM `t_counseling` where `COUNSELING_ID` ='$id' ");
 
while ($row = mysqli_fetch_array($sql))
{
$name = $row['STUD_NAME'];
$pdf = new PDF();
// Column headings
// Data loading
$pdf->SetFont('Arial','',14);
$pdf->AddPage();
$pdf->Body();
$pdf->Output('I', $name);
}
}
?>