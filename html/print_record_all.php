<?php
require('fpdf.php');
session_start();
if (!$_SESSION['Logged_In']) {
    header('Location:login.php');
    exit;
}
$acadOpt = '';
$semOpt = '';
$monthOpt = '';
$dayOpt = '';
$courseOpt = '';
$result = '';
class PDF extends FPDF
{

// Colored table
    public function Header()
    {
    

        $this->SetFont('Times', '', 11);
        // Move to the right
    
        // Framed title
    
        $this->Image('images/PUPLogo.png', 15, 5, 30, 30);
        $this->Ln(3);
        $this->Cell(130, 10, 'Republic of the Philippines', 0, 0, 'C');
        $this->SetFont('Times', 'B', 11);
        $this->Ln(2);
        $this->Cell(183, 15, 'POLYTECHNIC UNIVERSITY OF THE PHILIPPINES', 0, 0, 'C');
        $this->Ln(4);
        $this->Cell(133, 15, 'QUEZON CITY BRANCH', 0, 0, 'C');// Line break
        $this->SetDrawColor(0,0,0);
        $this->SetLineWidth(1); 
        $this->Line(15,40,195,40);
        $this->Ln(20);
        $this->SetFont('Arial', '', 11);
        $this->Cell(0, 15, 'All Records', 0, 0, 'C');
        $this->Ln(20);
    }
    // Colored table
    public function FancyTable($body)
    {
        // Colors, line width and bold font
        $this->SetFillColor(255, 0, 0);
        $this->SetTextColor(255);
        $this->SetDrawColor(128, 0, 0);
        $this->SetLineWidth(.3);
        $this->SetFont('', 'B');
        // Header
        $w = array(50, 50, 50, 40);
        for ($i=0;$i<count($body);$i++) {
            $this->Cell($w[$i], 7, $body[$i], 1, 0, 'C', true);
        }
        $this->Ln();
        // Color and font restoration
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('');
        // Data
    
        $fill = false;
        // $conn = mysqli_connect("localhost", "root", "", "pupqcdb");
        include ('config.php');

        // Check connection
        if (mysqli_connect_errno()) {
            printf("Connect failed: %s\n", mysqli_connect_error());
            exit();
        }

        if (isset($_REQUEST['view'])) {
            $acadOpt = $_REQUEST['acadOpt'];
            $semOpt = $_REQUEST['semOpt'];
            $monthOpt = $_REQUEST['monthOpt'];
            $dayOpt = $_REQUEST['dayOpt'];
            $courseOpt = $_REQUEST['courseOpt'];

            $actualQuery = " SELECT
  `s`.`Stud_NO` AS `STUD_NO`,
  CONCAT(`s`.`Stud_FNAME`, ' ', `s`.`Stud_LNAME`) AS `STUD_NAME`,
  `c`.`Couns_COUNSELING_TYPE` AS `COUNSELING_TYPE`,
  DATE_FORMAT(`c`.`Couns_DATE`, '%M %d %Y') AS `COUNSELING_DATE`,
  (
    SELECT
      GROUP_CONCAT(`a`.`Couns_APPROACH` SEPARATOR ', ')
    FROM
      `t_couns_approach` `a`
    WHERE
      (
        `a`.`Couns_ID_REFERENCE` = `c`.`Couns_ID`
      )
  ) AS `COUNSELING_APPROACH`,
  `c`.`Couns_BACKGROUND` AS `COUNSELING_BG`,
  `c`.`Couns_GOALS` AS `GOALS`,
  `c`.`Couns_COMMENT` AS `COUNS_COMMENT`,
  `c`.`Couns_RECOMMENDATION` AS `RECOMMENDATION`
FROM
  `t_counseling` `c`
  JOIN `t_couns_details` `cd` ON `c`.`Couns_ID` = `cd`.`Couns_ID_REFERENCE`
  JOIN `r_stud_profile` `s` ON `s`.`Stud_NO` = `cd`.`Stud_NO`
  JOIN `r_courses` `cr` ON `s`.`Stud_COURSE` = `cr`.`Course_CODE` ";
    
    $options = array();
    
    if (!empty($acadOpt) && $acadOpt != 'All') {
        $options[] = "cr.Course_CURR_YEAR = '$acadOpt'";
    }

    if (!empty($semOpt) && $semOpt != 'All') {
        $options[] = "c.Couns_SEMESTER =  '$semOpt'";
    } 

    if (!empty($monthOpt) && $monthOpt != 'All') {
        $options[] = "MONTH(c.Couns_DATE) = '$monthOpt'";
    }

    if (!empty($dayOpt) && $dayOpt != 'All') {
        $options[] = "DAY(c.Couns_DATE) = '$dayOpt'";
    }

    if (!empty($courseOpt) && $courseOpt != 'All') {
        $options[] = "s.Stud_COURSE = '$courseOpt'";
    }

    $query = $actualQuery;
    if (count($options)>0) {
        $query .= " WHERE ". implode(' AND ', $options);
    }

    $result = mysqli_query($db, $query);
    } else {
        $result =  mysqli_query($db, " SELECT
 `s`.`Stud_NO` AS `STUD_NO`,
 CONCAT(`s`.`Stud_FNAME`, ' ', `s`.`Stud_LNAME`) AS `STUD_NAME`,
 `c`.`Couns_COUNSELING_TYPE` AS `COUNSELING_TYPE`,
  DATE_FORMAT(`c`.`Couns_DATE`, '%M %d %Y') AS `COUNSELING_DATE`,
  
 
  
  (
  SELECT
      GROUP_CONCAT(`a`.`Couns_APPROACH` SEPARATOR ', ')
  FROM
      `t_couns_approach` `a`
  WHERE
      (
          `a`.`Couns_ID_REFERENCE` = `c`.`Couns_ID`
      )
) AS `COUNSELING_APPROACH`,
`c`.`Couns_BACKGROUND` AS `COUNSELING_BG`,
`c`.`Couns_GOALS` AS `GOALS`,
`c`.`Couns_COMMENT` AS `COUNS_COMMENT`,
`c`.`Couns_RECOMMENDATION` AS `RECOMMENDATION`
FROM
  (
      (
          `t_counseling` `c`
      JOIN `t_couns_details` `cd` ON
          (
              (
                  `c`.`Couns_ID` = `cd`.`Couns_ID_REFERENCE`
              )
          )
      )
  JOIN `r_stud_profile` `s` ON
      ((`s`.`Stud_NO` = `cd`.`Stud_NO`))
  )  ");
    }

        
        while ($row = mysqli_fetch_array($result)) {
            {
        $this->Cell($w[0], 7, $row[0], 'LR', 0, 'L', $fill);
        $this->Cell($w[1], 7, $row[1], 'LR', 0, 'L', $fill);
        $this->Cell($w[2], 7, $row[2], 'LR', 0, 'L', $fill);
        $this->Cell($w[3], 7, $row[3], 'LR', 0, 'L', $fill);
        $this->Ln();
        $fill = !$fill;
    }
        }
        // Closing line
        $this->Cell(array_sum($w), 0, '', 'T');
    }
}

$pdf = new PDF();
// Column headings
$body = array('Student Number', 'Student name', 'Type', 'Date');
// Data loading
$pdf->SetFont('Arial', '', 14);
$pdf->AddPage();
$pdf->FancyTable($body);
$pdf->Output('I', 'Counseling Report');
