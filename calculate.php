<?php
include "db.php";

$student = $_POST['student'];
$semester = $_POST['semester'];

$courses = $_POST['course'];
$credits = $_POST['credits'];
$grades = $_POST['grade'];

$totalPoints = 0;
$totalCredits = 0;

for($i=0;$i<count($courses);$i++){

$cr = $credits[$i];
$gr = $grades[$i];

$totalPoints += $cr * $gr;
$totalCredits += $cr;

}

$gpa = $totalPoints / $totalCredits;

if($gpa >= 3.7){
$color="success";
$text="Distinction";
}

elseif($gpa >=3){
$color="info";
$text="Merit";
}

elseif($gpa >=2){
$color="warning";
$text="Pass";
}

else{
$color="danger";
$text="Fail";
}

echo "<div class='alert alert-$color'>
GPA = ".number_format($gpa,2)." ($text)
</div>";

echo "<div class='progress mb-3'>
<div class='progress-bar bg-$color'
style='width:".($gpa/4*100)."%'>
</div>
</div>";

for($i=0;$i<count($courses);$i++){

$course=$courses[$i];
$cr=$credits[$i];
$gr=$grades[$i];

$sql="INSERT INTO gpa_records
(student_name,semester,course,credits,grade,gpa)
VALUES
('$student','$semester','$course','$cr','$gr','$gpa')";

$conn->query($sql);

}
?>