<?php
require('fpdf.php');
session_start();
include ('config.php');
if (!$_SESSION['Logged_In']) {
    header('Location:login.php');
    exit;
}
class PDF extends FPDF
{
    // Colored table
    public function Header()
    {
        // Select Arial bold 15
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
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(1);
        $this->Line(15, 40, 195, 40);

        $this->Ln(20);
    }
    // Colored table
    public function body()
    {
        $this->Cell(90);
        $this->Cell(18, 15, 'Student Information', 0, 0, 'C');
        $this->Ln(30);
    
   
        $fill = false;
        // $db = mysqli_connect("localhost", "root", "", "pupqcdb");
        include ('config.php');

        if (isset($_REQUEST['view'])) {
            $id = $_REQUEST['view'];
            $sql= mysqli_query($db, " SELECT
  `s`.`Stud_NO` AS `STUD_NO`,
  CONCAT(`s`.`Stud_FNAME`, ' ', `s`.`Stud_LNAME`) AS `STUD_NAME`,
  `c`.`Couns_COUNSELING_TYPE` AS `COUNSELING_TYPE`,
  `c`.`Nature_Of_Case` AS `CASE_NATURE`,
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
      JOIN `t_couns_details` `cd` ON (
        (
          `c`.`Couns_ID` = `cd`.`Couns_ID_REFERENCE`
        )
      )
    )
    JOIN `r_stud_profile` `s` ON ((`s`.`Stud_NO` = `cd`.`Stud_NO`))
  )
where
  `c`.`Couns_CODE` = '$id'");

            while ($row = mysqli_fetch_array($sql)) {
                $this->Cell(9, 9, 'Student Number: '.$row[0].'', 0, 1, 'L', $fill);
                $this->Cell(9, 9, 'Student Name: '.$row[1].'', 0, 1, 'L', $fill);
                $this->Cell(9, 9, 'Couseling Type: '.$row[2].'', 0, 1, 'L', $fill);
                $this->Cell(9, 9, 'Nature of The Case: '.$row[3].'', 0, 1, 'L', $fill);
                $this->Ln(10);
                $this->Write(9, 'Couseling Backgroud: '.$row[4].'', false);
                $this->Ln(20);
                $this->Write(9, 'Goals: '.$row[5].'', false);
                $this->Ln(20);
                $this->Write(9, 'Comments: '.$row[6].'', false);
                $this->Ln(20);
                $this->Write(9, 'Recommemdations: '.$row[7].'', false);
            }
        }
    }
}

if (isset($_REQUEST['view'])) {
    // $db = mysqli_connect("localhost", "root", "", "pupqcdb");
    $id = $_REQUEST['view'];
    $sql= mysqli_query($db, "SELECT
`c`.`Couns_CODE` AS `COUNSELING_CODE`,
DATE_FORMAT(`c`.`Couns_DATE`, '%W %M %d %Y') AS `COUNSELING_DATE`,
`c`.`Couns_COUNSELING_TYPE` AS `COUNSELING_TYPE`,
`c`.`Nature_Of_Case` AS `CASE_NATURE`,
`c`.`Couns_APPOINTMENT_TYPE` AS `APPOINTMENT_TYPE`,
`s`.`Stud_NO` AS `STUD_NO`,
CONCAT(`s`.`Stud_FNAME`, ' ', `s`.`Stud_LNAME`) AS `STUD_NAME`,
CONCAT(
    `s`.`Stud_COURSE`,
    ' ',
    `s`.`Stud_YEAR_LEVEL`,
    ' - ',
    `s`.`Stud_SECTION`
) AS `COURSE`,
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
)  where `c`.`Couns_CODE` ='$id' ");
 
    while ($row = mysqli_fetch_array($sql)) {
        $name = $row['STUD_NAME'];
        $pdf = new PDF();
        // Column headings
        // Data loading
        $pdf->SetFont('Arial', '', 14);
        $pdf->AddPage();
        $pdf->Body();
        $pdf->Output('I', $name);
    }
}
