<?php

// Get the current view mode (list, grid, table, map, calendar)
$view_mode = isset($_GET['view']) ? $_GET['view'] : 'list';

// Get search query if present
$search_query = isset($_GET['search']) ? $_GET['search'] : '';

// Get category filter if present
$category_filter = isset($_GET['category']) ? (int)$_GET['category'] : 0;

// Get event ID for detail view
$event_id = isset($_GET['event_id']) ? (int)$_GET['event_id'] : 0;

// Fetch categories for filter
$stmt = $pdo->query("SELECT id, name FROM categories ORDER BY name");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Base query for events
$query = "SELECT e.*, c.name as category_name
          FROM events e
          LEFT JOIN categories c ON e.category_id = c.id
          WHERE 1";
$params = [];

// Add search condition if search query is provided
if (!empty($search_query)) {
    $query .= " AND (e.title LIKE ? OR e.description LIKE ?)";
    $params[] = "%$search_query%";
    $params[] = "%$search_query%";
}

// Add category filter if selected
if ($category_filter > 0) {
    $query .= " AND e.category_id = ?";
    $params[] = $category_filter;
}

// Add order by date
$query .= " ORDER BY e.event_date";

// Prepare and execute query
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>