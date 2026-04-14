<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>GPA Calculator</title>

<link rel="stylesheet"
href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="script.js"></script>

</head>

<body class="container mt-4">

<h2>GPA Calculator</h2>

<div id="result"></div>

<form id="gpaForm">

<input class="form-control mb-2" name="student"
placeholder="Student Name" required>

<input class="form-control mb-2" name="semester"
placeholder="Semester" required>

<div id="courses">

<div class="row course-row mb-2">

<div class="col">
<input type="text" name="course[]" class="form-control"
placeholder="Course">
</div>

<div class="col">
<input type="number" name="credits[]" class="form-control"
placeholder="Credits">
</div>

<div class="col">
<select name="grade[]" class="form-control">
<option value="4">A</option>
<option value="3">B</option>
<option value="2">C</option>
<option value="1">D</option>
<option value="0">F</option>
</select>
</div>

</div>

</div>

<button type="button" id="addCourse"
class="btn btn-secondary mb-2">Add Course</button>

<br>

<button class="btn btn-primary">Calculate GPA</button>

</form>

<br>

<a href="export.php" class="btn btn-success">Export CSV</a>

</body>
</html>