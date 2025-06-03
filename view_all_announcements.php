<?php
session_start();
include 'connection.php';

// Fetch announcements with department names
$query = "SELECT 
            a.announcement_id AS id, 
            a.title, 
            a.message, 
            a.image, 
            a.created_at, 
            d.name AS department_name
          FROM announcements a
          LEFT JOIN department d ON a.department_id = d.id
          WHERE a.status = 'approved'
          ORDER BY a.created_at DESC";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>All Announcements - Department Announcement System</title>

  <!-- DataTables CSS -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

  <link rel="icon" href="images/iaa_logo.png">

  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 2rem;
      background: #f5f7fb;
    }
    h1 {
      color: #004080;
      margin-bottom: 1rem;
    }
    table.dataTable thead {
      background-color: #004080;
      color: white;
    }
    table.dataTable tbody tr:nth-child(even) {
      background-color: #f0f4f8;
    }
    img.announcement-image {
      max-width: 100px;
      max-height: 70px;
      object-fit: cover;
      border-radius: 4px;
    }
  </style>
</head>
<body>

  <h1>All Announcements</h1>

  <table id="announcementsTable" class="display" style="width:100%">
    <thead>
      <tr>
        <th>Title</th>
        <th>Department</th>
        <th>Description</th>
        <th>Image</th>
        <th>Date</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <tr>
          <td><?= htmlspecialchars($row['title']) ?></td>
          <td><?= htmlspecialchars($row['department_name'] ?? 'N/A') ?></td>
          <td><?= nl2br(htmlspecialchars($row['message'])) ?></td>
          <td>
            <?php if (!empty($row['image'])): ?>
              <img src="images/<?= htmlspecialchars($row['image']) ?>" alt="Announcement Image" class="announcement-image" />
            <?php else: ?>
              No Image
            <?php endif; ?>
          </td>
          <td><?= date('F j, Y', strtotime($row['created_at'])) ?></td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>

  <!-- jQuery and DataTables JS -->
  <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

  <script>
    $(document).ready(function() {
      $('#announcementsTable').DataTable({
        "pageLength": 10,
        "order": [[4, "desc"]]
      });
    });
  </script>

</body>
</html>
