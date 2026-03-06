$(document).ready(function () {

    // ─────────────────────────────────────────
    // 1. Load History from localStorage
    // ─────────────────────────────────────────
    loadHistory();

    // ─────────────────────────────────────────
    // 2. Add Course Row
    // ─────────────────────────────────────────
    $('#addCourse').click(function () {
        var courseNames = [];
        $('[name="course[]"]').each(function () {
            courseNames.push($(this).val().trim().toLowerCase());
        });

        var row = $('.course-row').first().clone();
        row.find('input').val('');
        row.find('.col-md-1').html(
            '<button type="button" class="btn btn-danger btn-sm remove-row" title="Remove">✕</button>'
        );
        $('#courses').append(row);
    });

    // ─────────────────────────────────────────
    // 3. Remove Course Row
    // ─────────────────────────────────────────
    $(document).on('click', '.remove-row', function () {
        if ($('.course-row').length > 1) {
            $(this).closest('.course-row').remove();
        } else {
            showAlert('result', 'warning', '⚠️ You must have at least one course.');
        }
    });

    // ─────────────────────────────────────────
    // 4. Submit Form & Calculate GPA
    // ─────────────────────────────────────────
    $('#gpaForm').submit(function (e) {
        e.preventDefault();

        var studentName = $('#studentName').val().trim();
        var semester    = $('#semester').val().trim();

        // Validate student info
        if (studentName === '' || semester === '') {
            showAlert('result', 'warning', '⚠️ Please enter your name and semester.');
            return;
        }

        // Validate courses
        var courses = [];
        var courseNames = [];
        var valid = true;
        var hasDuplicate = false;

        $('.course-row').each(function () {
            var name    = $(this).find('[name="course[]"]').val().trim();
            var credits = parseFloat($(this).find('[name="credits[]"]').val());
            var grade   = parseFloat($(this).find('[name="grade[]"]').val());

            if (name === '') { valid = false; return false; }
            if (isNaN(credits) || credits <= 0 || credits > 6) { valid = false; return false; }

            // Duplicate check
            if (courseNames.includes(name.toLowerCase())) {
                hasDuplicate = true;
                return false;
            }
            courseNames.push(name.toLowerCase());
            courses.push({ name, credits, grade });
        });

        if (!valid) {
            showAlert('result', 'warning', '⚠️ Please fill all fields correctly. Credits must be between 1 and 6.');
            return;
        }
        if (hasDuplicate) {
            showAlert('result', 'danger', '❌ Duplicate course name detected! Each course must have a unique name.');
            return;
        }

        // ── Calculate GPA ──
        var totalPoints  = 0;
        var totalCredits = 0;
        var tableRows    = '';

        courses.forEach(function (c) {
            var pts = c.credits * c.grade;
            totalPoints  += pts;
            totalCredits += c.credits;
            var gradeLetter = gradePointToLetter(c.grade);
            tableRows += '<tr><td>' + c.name + '</td><td>' + c.credits +
                         '</td><td>' + gradeLetter + '</td><td>' + pts.toFixed(1) + '</td></tr>';
        });

        var gpa            = totalPoints / totalCredits;
        var interpretation = getInterpretation(gpa);
        var alertClass     = getAlertClass(gpa);
        var progressColor  = getProgressColor(gpa);
        var progressPct    = ((gpa / 4.0) * 100).toFixed(1);

        // ── Show Result ──
        var tableHtml =
            '<table class="table table-bordered mt-3">' +
            '<thead class="thead-dark"><tr>' +
            '<th>Course</th><th>Credits</th><th>Grade</th><th>Grade Points</th>' +
            '</tr></thead><tbody>' + tableRows + '</tbody></table>';

        $('#result').html(
            '<div class="alert ' + alertClass + ' font-weight-bold text-center" style="font-size:1.1rem;">' +
            '🎓 ' + studentName + ' — ' + semester + '<br>' +
            'GPA: <span style="font-size:1.5rem;">' + gpa.toFixed(2) + '</span> — ' + interpretation +
            '</div>' + tableHtml
        );

        // ── Progress Bar ──
        $('#progressSection').show();
        $('#gpaProgressBar')
            .css({ width: progressPct + '%', 'background-color': progressColor })
            .text('GPA: ' + gpa.toFixed(2) + ' / 4.0');

        // ── Show Export Button ──
        $('#exportCSV').show().data('courses', courses).data('gpa', gpa)
            .data('student', studentName).data('semester', semester);

        // ── Save to History ──
        saveToHistory(studentName, semester, gpa, interpretation, courses);
    });

    // ─────────────────────────────────────────
    // 5. Export CSV
    // ─────────────────────────────────────────
    $('#exportCSV').click(function () {
        var courses   = $(this).data('courses');
        var gpa       = $(this).data('gpa');
        var student   = $(this).data('student');
        var semester  = $(this).data('semester');

        var csv = 'Student,' + student + '\n';
        csv    += 'Semester,' + semester + '\n\n';
        csv    += 'Course,Credits,Grade,Grade Points\n';

        courses.forEach(function (c) {
            csv += c.name + ',' + c.credits + ',' +
                   gradePointToLetter(c.grade) + ',' + (c.credits * c.grade).toFixed(1) + '\n';
        });
        csv += '\nFinal GPA,' + gpa.toFixed(2) + ',' + getInterpretation(gpa) + '\n';

        var blob = new Blob([csv], { type: 'text/csv' });
        var url  = URL.createObjectURL(blob);
        var a    = $('<a>').attr({ href: url, download: student + '_GPA_' + semester + '.csv' });
        $('body').append(a);
        a[0].click();
        a.remove();
    });

    // ─────────────────────────────────────────
    // 6. Clear History
    // ─────────────────────────────────────────
    $('#clearHistory').click(function () {
        if (confirm('Are you sure you want to clear all history?')) {
            localStorage.removeItem('gpaHistory');
            loadHistory();
        }
    });

    // ─────────────────────────────────────────
    // Helper Functions
    // ─────────────────────────────────────────

    function getInterpretation(gpa) {
        if (gpa >= 3.7) return 'Distinction 🏅';
        if (gpa >= 3.0) return 'Merit 🥈';
        if (gpa >= 2.0) return 'Pass ✅';
        return 'Fail ❌';
    }

    function getAlertClass(gpa) {
        if (gpa >= 3.7) return 'alert-success';
        if (gpa >= 3.0) return 'alert-info';
        if (gpa >= 2.0) return 'alert-warning';
        return 'alert-danger';
    }

    function getProgressColor(gpa) {
        if (gpa >= 3.7) return '#28a745';
        if (gpa >= 3.0) return '#17a2b8';
        if (gpa >= 2.0) return '#ffc107';
        return '#dc3545';
    }

    function gradePointToLetter(pts) {
        if (pts >= 4.0) return 'A';
        if (pts >= 3.0) return 'B';
        if (pts >= 2.0) return 'C';
        if (pts >= 1.0) return 'D';
        return 'F';
    }

    function showAlert(targetId, type, message) {
        $('#' + targetId).html('<div class="alert alert-' + type + '">' + message + '</div>');
    }

    function saveToHistory(name, semester, gpa, interpretation, courses) {
        var history = JSON.parse(localStorage.getItem('gpaHistory') || '[]');
        history.unshift({
            name, semester,
            gpa: gpa.toFixed(2),
            interpretation,
            courses,
            date: new Date().toLocaleDateString()
        });
        // Keep max 20 records
        if (history.length > 20) history = history.slice(0, 20);
        localStorage.setItem('gpaHistory', JSON.stringify(history));
        loadHistory();
    }

    function loadHistory() {
        var history = JSON.parse(localStorage.getItem('gpaHistory') || '[]');
        if (history.length === 0) {
            $('#historySection').html('<p class="text-muted text-center py-3">No records yet.</p>');
            return;
        }

        var html = '<table class="table table-hover mb-0"><thead class="thead-light"><tr>' +
            '<th>#</th><th>Student</th><th>Semester</th><th>GPA</th><th>Result</th><th>Date</th><th></th>' +
            '</tr></thead><tbody>';

        history.forEach(function (rec, i) {
            var badgeColor = rec.gpa >= 3.7 ? 'success' :
                             rec.gpa >= 3.0 ? 'info' :
                             rec.gpa >= 2.0 ? 'warning' : 'danger';
            html += '<tr>' +
                '<td>' + (i + 1) + '</td>' +
                '<td>' + rec.name + '</td>' +
                '<td>' + rec.semester + '</td>' +
                '<td><strong>' + rec.gpa + '</strong></td>' +
                '<td><span class="badge badge-' + badgeColor + '">' + rec.interpretation + '</span></td>' +
                '<td>' + rec.date + '</td>' +
                '<td><button class="btn btn-sm btn-outline-danger delete-record" data-index="' + i + '">✕</button></td>' +
                '</tr>';
        });

        html += '</tbody></table>';
        $('#historySection').html(html);
    }

    // Delete single record
    $(document).on('click', '.delete-record', function () {
        var index   = $(this).data('index');
        var history = JSON.parse(localStorage.getItem('gpaHistory') || '[]');
        history.splice(index, 1);
        localStorage.setItem('gpaHistory', JSON.stringify(history));
        loadHistory();
    });

});
