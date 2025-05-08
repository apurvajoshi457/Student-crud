<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

include 'db.php';

// Set the number of records per page
$records_per_page = 10;

// Get the total number of records in the database
$total_records_sql = "SELECT COUNT(*) FROM students";
$result = $conn->query($total_records_sql);
$row = $result->fetch_row();
$total_records = $row[0];

// Calculate the total number of pages
$total_pages = ceil($total_records / $records_per_page);

// Get the current page from the URL (default to page 1 if not set)
$page = isset($_GET['page']) ? $_GET['page'] : 1;
if ($page < 1) $page = 1;

// Calculate the starting record for the query (offset)
$start_from = ($page - 1) * $records_per_page;

// Fetch the records for the current page
$sql = "SELECT * FROM students LIMIT $start_from, $records_per_page";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student List</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- jQuery + Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function confirmDelete(name) {
            return confirm("Are you sure you want to delete student: " + name + "?");
        }
    </script>
</head>
<body class="bg-light">

<div class="container mt-5">
    <!-- Greeting and Navigation -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Welcome  <?php echo htmlspecialchars($_SESSION['admin']); ?> 👋 to student management system</h2>
        <div>
            <a href="add.php" class="btn btn-success me-2">➕ Add Student</a>
            <a href="logout.php" class="btn btn-danger">🚪 Logout</a>
        </div>
    </div>

    <!-- Student Table -->
    <div class="card shadow">
        <div class="card-body">
            <h4 class="card-title mb-4">Student List</h4>
            <table class="table table-bordered table-hover align-middle text-center">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>
                            <td>{$row['id']}</td>
                            <td>".htmlspecialchars($row['name'])."</td>
                            <td>".htmlspecialchars($row['email'])."</td>
                            <td>".htmlspecialchars($row['phone'])."</td>
                            <td>
                                <a href='edit.php?id={$row['id']}' class='btn btn-sm btn-primary'>✏️ Edit</a>
                                <a href='delete.php?id={$row['id']}' class='btn btn-sm btn-danger' onclick=\"return confirmDelete('{$row['name']}')\">❌ Delete</a>
                            </td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No students found.</td></tr>";
                }
                ?>
                </tbody>
            </table>

            <!-- Pagination Links -->
            <nav>
                <ul class="pagination justify-content-center">
                    <?php
                    // Previous button
                    if ($page > 1) {
                        echo '<li class="page-item"><a class="page-link" href="index.php?page=' . ($page - 1) . '">Previous</a></li>';
                    }

                    // Page links
                    for ($i = 1; $i <= $total_pages; $i++) {
                        if ($i == $page) {
                            echo '<li class="page-item active"><a class="page-link" href="index.php?page=' . $i . '">' . $i . '</a></li>';
                        } else {
                            echo '<li class="page-item"><a class="page-link" href="index.php?page=' . $i . '">' . $i . '</a></li>';
                        }
                    }

                    // Next button
                    if ($page < $total_pages) {
                        echo '<li class="page-item"><a class="page-link" href="index.php?page=' . ($page + 1) . '">Next</a></li>';
                    }
                    ?>
                </ul>
            </nav>
        </div>
    </div>
</div>

</body>
</html>
                    