<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css"
        integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI=" crossorigin="" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"
        integrity="sha256-WBkoXOwTeyKclOHuWtc+i2uENFpDZ9YPdf5Hf+D7ewM=" crossorigin=""></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
</head>

<body class="bg-gray-50 dark:bg-gray-900">
    <div class="container mx-auto px-4 py-8">
        <header class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800 dark:text-white mb-4">Event Management System</h1>

            <!-- Search & Filter Bar -->
            <div class="flex flex-col lg:flex-row items-center justify-between space-y-4 lg:space-y-0 mb-6">
                <form action="" method="GET"
                    class="w-full lg:w-auto flex flex-col lg:flex-row space-y-2 lg:space-y-0 lg:space-x-4">
                    <input type="hidden" name="view" value="<?php echo $view_mode; ?>">
                    <div class="flex-grow">
                        <input type="text" name="search" placeholder="Search events..."
                            value="<?php echo htmlspecialchars($search_query); ?>"
                            class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-base">
                    </div>
                    <div class="w-full lg:w-auto">
                        <select name="category"
                            class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-base">
                            <option value="0">All Categories</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?php echo $category['id']; ?>" <?php echo $category_filter == $category['id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($category['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <button type="submit"
                            class="w-full lg:w-auto px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <i class="fa fa-search"></i>
                        </button>
                    </div>
                </form>

                <!-- View Mode Switcher -->
                <div class="flex space-x-2">
                    <a href="?view=list<?php echo !empty($search_query) ? '&search=' . urlencode($search_query) : '';
                    echo $category_filter > 0 ? '&category=' . $category_filter : ''; ?>"
                        class="px-4 py-2 rounded-lg <?php echo $view_mode == 'list' ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'; ?>">
                        List
                    </a>
                    <a href="?view=grid<?php echo !empty($search_query) ? '&search=' . urlencode($search_query) : '';
                    echo $category_filter > 0 ? '&category=' . $category_filter : ''; ?>"
                        class="px-4 py-2 rounded-lg <?php echo $view_mode == 'grid' ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'; ?>">
                        Grid
                    </a>
                    <a href="?view=table<?php echo !empty($search_query) ? '&search=' . urlencode($search_query) : '';
                    echo $category_filter > 0 ? '&category=' . $category_filter : ''; ?>"
                        class="px-4 py-2 rounded-lg <?php echo $view_mode == 'table' ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'; ?>">
                        Table
                    </a>
                    <a href="?view=map<?php echo !empty($search_query) ? '&search=' . urlencode($search_query) : '';
                    echo $category_filter > 0 ? '&category=' . $category_filter : ''; ?>"
                        class="px-4 py-2 rounded-lg <?php echo $view_mode == 'map' ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'; ?>">
                        Map
                    </a>
                    <a href="?view=calendar<?php echo !empty($search_query) ? '&search=' . urlencode($search_query) : '';
                    echo $category_filter > 0 ? '&category=' . $category_filter : ''; ?>"
                        class="px-4 py-2 rounded-lg <?php echo $view_mode == 'calendar' ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'; ?>">
                        Calendar
                    </a>
                </div>
            </div>
        </header>