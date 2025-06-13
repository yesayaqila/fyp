<?php
include 'db-skpp.php';

$searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = isset($_GET['perPage']) ? (int)$_GET['perPage'] : 8;
$offset = ($page - 1) * $perPage;

// Count total rows for pagination
if ($searchTerm !== '') {
    $countStmt = $conn->prepare("SELECT COUNT(*) AS total FROM teachers 
                                WHERE name LIKE CONCAT('%', ?, '%') 
                                   OR username LIKE CONCAT('%', ?, '%') 
                                   OR position LIKE CONCAT('%', ?, '%')");
    $countStmt->bind_param("sss", $searchTerm, $searchTerm, $searchTerm);
    $countStmt->execute();
    $countResult = $countStmt->get_result()->fetch_assoc();
    $totalRows = $countResult['total'];
    $countStmt->close();
} else {
    $countQuery = "SELECT COUNT(*) AS total FROM teachers";
    $totalRows = $conn->query($countQuery)->fetch_assoc()['total'];
}

$totalPages = ceil($totalRows / $perPage);

// Fetch paginated data
if ($searchTerm !== '') {
    $stmt = $conn->prepare("SELECT t.teacher_id, t.username, t.name, t.position, p.panitia_name
                        FROM teachers t
                        LEFT JOIN panitia p ON p.head_teacher_id = t.teacher_id
                        WHERE t.name LIKE CONCAT('%', ?, '%') 
                           OR t.username LIKE CONCAT('%', ?, '%') 
                           OR t.position LIKE CONCAT('%', ?, '%')
                        ORDER BY 
                            CASE 
                                WHEN t.position LIKE '%Guru Besar%' THEN 1
                                WHEN t.position LIKE '%GPK 1%' THEN 2
                                WHEN t.position LIKE '%GPK%' THEN 3
                                ELSE 4
                            END,
                            t.name ASC
                        LIMIT ?, ?");
    $stmt->bind_param("sssii", $searchTerm, $searchTerm, $searchTerm, $offset, $perPage);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $stmt = $conn->prepare("SELECT t.teacher_id, t.username, t.name, t.position, p.panitia_name
                        FROM teachers t
                        LEFT JOIN panitia p ON p.head_teacher_id = t.teacher_id
                        ORDER BY 
                            CASE 
                                WHEN t.position LIKE '%Guru Besar%' THEN 1
                                WHEN t.position LIKE '%GPK 1%' THEN 2
                                WHEN t.position LIKE '%GPK%' THEN 3
                                ELSE 4
                            END,
                            t.name ASC
                        LIMIT ?, ?");
    $stmt->bind_param("ii", $offset, $perPage);
    $stmt->execute();
    $result = $stmt->get_result();
}

// Display table
echo '<table class="teacher-table">
<thead>
    <tr>
        <th>Nama Pengguna</th>
        <th>Nama Penuh</th>
        <th>Jawatan</th>
        <th>Tindakan</th>
    </tr>
</thead>
<tbody>';

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
            <td>{$row['username']}</td>
            <td>{$row['name']}</td>
            <td>
                {$row['position']}
                " . (!empty($row['panitia_name']) && $row['position'] === 'Ketua Panitia'
                ? " - <span style='font-weight: normal;'>{$row['panitia_name']}</span>"
                : "") . "
            </td>

            <td><a href='guru-detail.php?id={$row['teacher_id']}' class='btn-view'>Lihat Maklumat</a></td>
          </tr>";
    }
} else {
    echo "<tr><td colspan='4'>Tiada guru dijumpai.</td></tr>";
}

echo '</tbody></table>';

// Pagination links
echo '<div class="pagination">';
for ($i = 1; $i <= $totalPages; $i++) {
    $activeClass = ($i == $page) ? ' style="font-weight: bold; text-decoration: underline;"' : '';
    echo "<a class='page-link' href='?page=$i&perPage=$perPage&search=" . urlencode($searchTerm) . "' $activeClass>$i</a> ";
}
echo '</div>';

$conn->close();
