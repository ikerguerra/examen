<?php

// // Get the current view mode (list, grid, table, map, calendar)
// $view_mode = isset($_GET['view']) ? $_GET['view'] : 'list';

// // Get search query if present
// $search_query = isset($_GET['search']) ? $_GET['search'] : '';

// // Get category filter if present
// $category_filter = isset($_GET['category']) ? (int)$_GET['category'] : 0;

// // Get event ID for detail view
// $event_id = isset($_GET['event_id']) ? (int)$_GET['event_id'] : 0;

// // Fetch categories for filter
// $stmt = $pdo->query("SELECT id, name FROM categories ORDER BY name");
// $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// // Base query for events
// $query = "SELECT e.*, c.name as category_name
//           FROM events e
//           LEFT JOIN categories c ON e.category_id = c.id
//           WHERE 1";
// $params = [];

// // Add search condition if search query is provided
// if (!empty($search_query)) {
//     $query .= " AND (e.title LIKE ? OR e.description LIKE ?)";
//     $params[] = "%$search_query%";
//     $params[] = "%$search_query%";
// }

// // Add category filter if selected
// if ($category_filter > 0) {
//     $query .= " AND e.category_id = ?";
//     $params[] = $category_filter;
// }

// // Add order by date
// $query .= " ORDER BY e.event_date";

// // Prepare and execute query
// $stmt = $pdo->prepare($query);
// $stmt->execute($params);
// $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get the current view mode (list, grid, table, map, calendar)
$view_mode = isset($_GET['view']) ? $_GET['view'] : 'list';


// Get search query if present
$search_query = isset($_GET['search']) ? trim($_GET['search']) : ''; // Trim whitespace


// Get category filter if present
$category_filter = isset($_GET['category']) ? (int)$_GET['category'] : 0;


// Get event ID for detail view
$event_id = isset($_GET['event_id']) ? (int)$_GET['event_id'] : 0;


// Fetch categories for filter using mysqli_query
$categories = [];
$sql_categories = "SELECT id, name FROM categories ORDER BY name";
$result_categories = mysqli_query($conn, $sql_categories);


if ($result_categories) {
    $categories = mysqli_fetch_all($result_categories, MYSQLI_ASSOC);
    mysqli_free_result($result_categories); // Free result set
} else {
    echo "Error fetching categories: " . mysqli_error($conn);
    // Decide if you want to die() here or continue without categories
}


// Base query for events
$query = "SELECT e.*, c.name as category_name
          FROM events e
          LEFT JOIN categories c ON e.category_id = c.id
          WHERE 1";
$params = [];
$types = ""; // String for parameter types (i=integer, s=string, d=double, b=blob)


// Add search condition if search query is provided
if (!empty($search_query)) {
    $query .= " AND (e.title LIKE ? OR e.description LIKE ?)";
    $search_param = "%" . $search_query . "%";
    $params[] = $search_param; // Add param for title
    $params[] = $search_param; // Add param for description
    $types .= "ss"; // Two string parameters
}


// Add category filter if selected
if ($category_filter > 0) {
    $query .= " AND e.category_id = ?";
    $params[] = $category_filter; // Add category ID param
    $types .= "i"; // One integer parameter
}


// Add order by date
$query .= " ORDER BY e.event_date";


// Prepare and execute query for events using mysqli prepared statements
$events = [];
$stmt = mysqli_prepare($conn, $query);
?>