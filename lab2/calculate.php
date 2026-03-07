<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_POST['course'], $_POST['credits'], $_POST['grade'])) {

    $courses      = $_POST['course'];
    $credits      = $_POST['credits'];
    $grades       = $_POST['grade'];
    $totalPoints  = 0;
    $totalCredits = 0;
    $courseNames  = [];
    $rows         = [];

    // Validation
    for ($i = 0; $i < count($courses); $i++) {
        $course = trim(htmlspecialchars($courses[$i]));
        $cr     = floatval($credits[$i]);
        $g      = floatval($grades[$i]);

        if ($course === '') {
            echo json_encode(['success' => false, 'message' => 'Course name cannot be empty.']);
            exit;
        }
        if ($cr <= 0 || $cr > 6) {
            echo json_encode(['success' => false, 'message' => 'Credits must be between 1 and 6.']);
            exit;
        }
        // Duplicate check
        if (in_array(strtolower($course), $courseNames)) {
            echo json_encode(['success' => false, 'message' => "Duplicate course: $course"]);
            exit;
        }
        $courseNames[] = strtolower($course);

        $pts           = $cr * $g;
        $totalPoints  += $pts;
        $totalCredits += $cr;

        $gradeLetter = gradeToLetter($g);
        $rows[]      = [
            'course'  => $course,
            'credits' => $cr,
            'grade'   => $gradeLetter,
            'pts'     => $pts
        ];
    }

    if ($totalCredits <= 0) {
        echo json_encode(['success' => false, 'message' => 'No valid courses entered.']);
        exit;
    }

    $gpa            = $totalPoints / $totalCredits;
    $interpretation = getInterpretation($gpa);

    // Build Bootstrap table
    $tableHtml  = '<table class="table table-bordered mt-3">';
    $tableHtml .= '<thead class="thead-dark"><tr>
                    <th>Course</th><th>Credits</th>
                    <th>Grade</th><th>Grade Points</th>
                   </tr></thead><tbody>';

    foreach ($rows as $row) {
        $tableHtml .= "<tr>
            <td>{$row['course']}</td>
            <td>{$row['credits']}</td>
            <td>{$row['grade']}</td>
            <td>{$row['pts']}</td>
        </tr>";
    }
    $tableHtml .= '</tbody></table>';

    echo json_encode([
        'success'        => true,
        'gpa'            => round($gpa, 2),
        'interpretation' => $interpretation,
        'message'        => 'Your GPA is ' . number_format($gpa, 2) . ' (' . $interpretation . ')',
        'tableHtml'      => $tableHtml,
    ]);

} else {
    echo json_encode(['success' => false, 'message' => 'Data not received.']);
}

// ── Helpers ──
function getInterpretation($gpa) {
    if ($gpa >= 3.7) return 'Distinction';
    if ($gpa >= 3.0) return 'Merit';
    if ($gpa >= 2.0) return 'Pass';
    return 'Fail';
}

function gradeToLetter($pts) {
    if ($pts >= 4.0) return 'A';
    if ($pts >= 3.0) return 'B';
    if ($pts >= 2.0) return 'C';
    if ($pts >= 1.0) return 'D';
    return 'F';
}
?>
