<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>GPA Calculator - Homework</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container py-4">

    <div class="text-center mb-4">
        <h1 class="display-5 font-weight-bold text-primary">🎓 GPA Calculator</h1>
        <p class="text-muted">Calculate and track your academic performance</p>
    </div>

    <!-- Result Section -->
    <div id="result" class="mb-4"></div>

    <!-- GPA Progress Bar -->
    <div id="progressSection" style="display:none;" class="mb-4">
        <label><strong>GPA Progress (0 - 4.0)</strong></label>
        <div class="progress" style="height:30px; border-radius:15px;">
            <div id="gpaProgressBar" class="progress-bar progress-bar-striped progress-bar-animated"
                 role="progressbar" style="width:0%">
            </div>
        </div>
        <div class="d-flex justify-content-between text-muted small mt-1">
            <span>0 - Fail</span>
            <span>2.0 - Pass</span>
            <span>3.0 - Merit</span>
            <span>3.7 - Distinction</span>
            <span>4.0</span>
        </div>
    </div>

    <!-- Main Card -->
    <div class="card shadow mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">📚 Enter Your Courses</h5>
        </div>
        <div class="card-body">
            <form id="gpaForm">
                <!-- Student Info -->
                <div class="form-row mb-3">
                    <div class="col-md-6">
                        <label><strong>Student Name</strong></label>
                        <input type="text" id="studentName" class="form-control"
                               placeholder="e.g. Ahmed Ali" required>
                    </div>
                    <div class="col-md-6">
                        <label><strong>Semester</strong></label>
                        <input type="text" id="semester" class="form-control"
                               placeholder="e.g. Semester 1 - 2024" required>
                    </div>
                </div>

                <hr>

                <!-- Course Rows -->
                <div id="courses">
                    <div class="course-row form-row mb-2 align-items-center">
                        <div class="col-md-5">
                            <input type="text" name="course[]" class="form-control"
                                   placeholder="Course name e.g. Mathematics" required>
                        </div>
                        <div class="col-md-3">
                            <input type="number" name="credits[]" class="form-control"
                                   placeholder="Credits (1-6)" min="1" max="6" required>
                        </div>
                        <div class="col-md-3">
                            <select name="grade[]" class="form-control">
                                <option value="4.0">A / A+ (Excellent)</option>
                                <option value="3.0">B (Good)</option>
                                <option value="2.0">C (Average)</option>
                                <option value="1.0">D (Below Average)</option>
                                <option value="0.0">F (Fail)</option>
                            </select>
                        </div>
                        <div class="col-md-1 text-center">
                            <span class="text-muted">—</span>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2 mt-3">
                    <button type="button" id="addCourse" class="btn btn-secondary mr-2">
                        ➕ Add Course
                    </button>
                    <button type="submit" class="btn btn-primary">
                        🧮 Calculate GPA
                    </button>
                    <button type="button" id="exportCSV" class="btn btn-success ml-auto" style="display:none;">
                        📥 Export CSV
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- History Section -->
    <div class="card shadow">
        <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">📜 Previous GPA Records</h5>
            <button id="clearHistory" class="btn btn-sm btn-outline-light">🗑 Clear All</button>
        </div>
        <div class="card-body p-0">
            <div id="historySection">
                <p class="text-muted text-center py-3">No records yet.</p>
            </div>
        </div>
    </div>

</div>

<!-- jQuery + Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="script.js"></script>
</body>
</html>
