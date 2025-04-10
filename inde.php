<?php require 'config/conexion.php' ?>
<?php include 'consultas.php' ?>
<?php include 'includes/_header.php' ?>

<main>
    <?php if ($event_id > 0): ?>
        <?php
              // Fetch single event details using mysqli prepared statements
              $event = null; // Initialize event
              $sql_single = "SELECT e.*, c.name as category_name
                             FROM events e
                             LEFT JOIN categories c ON e.category_id = c.id
                             WHERE e.id = ?";
              $stmt_single = mysqli_prepare($conn, $sql_single);


              if ($stmt_single) {
                  // Bind the integer parameter
                  mysqli_stmt_bind_param($stmt_single, 'i', $event_id);


                  // Execute
                  if (mysqli_stmt_execute($stmt_single)) {
                      // Get result
                      $result_single = mysqli_stmt_get_result($stmt_single);
                      if ($result_single) {
                          // Fetch the single row
                          $event = mysqli_fetch_assoc($result_single);
                      } else {
                          echo "Error getting result for single event: " . mysqli_stmt_error($stmt_single);
                      }
                  } else {
                      echo "Error executing single event query: " . mysqli_stmt_error($stmt_single);
                  }
                  // Close statement
                  mysqli_stmt_close($stmt_single);
              } else {
                  echo "Error preparing single event statement: " . mysqli_error($conn);
              }


              if ($event): // Check if event was successfully fetched
              ?>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
                <div class="p-6">
                    <div class="flex justify-between items-start">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                                <?php echo htmlspecialchars($event['title']); ?>
                            </h2>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                                <span
                                    class="inline-block bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200 rounded-full px-3 py-1 text-sm font-semibold mr-2">
                                    <?php echo htmlspecialchars($event['category_name'] ?? 'Uncategorized'); ?>
                                </span>
                                <span>
                                    <?php echo date('F j, Y - g:i A', strtotime($event['event_date'])); ?>
                                </span>
                            </p>
                        </div>
                        <a href="?view=<?php echo $view_mode; ?><?php echo !empty($search_query) ? '&search=' . urlencode($search_query) : '';
                           echo $category_filter > 0 ? '&category=' . $category_filter : ''; ?>"
                            class="text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300">
                            Back to Events
                        </a>
                    </div>

                    <?php if (!empty($event['image_url'])): ?>
                        <div class="mb-6">
                            <img src="<?php echo htmlspecialchars($event['image_url']); ?>"
                                alt="<?php echo htmlspecialchars($event['title']); ?>" class="w-full h-64 object-cover rounded-lg">
                        </div>
                    <?php endif; ?>

                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-2">Description</h3>
                        <p class="text-gray-700 dark:text-gray-300">
                            <?php echo nl2br(htmlspecialchars($event['description'])); ?>
                        </p>
                    </div>

                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-2">Location</h3>
                        <p class="text-gray-700 dark:text-gray-300 mb-4"><?php echo htmlspecialchars($event['location']); ?></p>

                        <?php if (!empty($event['latitude']) && !empty($event['longitude'])): ?>
                            <div id="detailMap" class="h-64 rounded-lg"></div>
                            <script>
                                document.addEventListener('DOMContentLoaded', function () {
                                    const map = L.map('detailMap').setView([<?php echo $event['latitude']; ?>, <?php echo $event['longitude']; ?>], 15);

                                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                                    }).addTo(map);

                                    L.marker([<?php echo $event['latitude']; ?>, <?php echo $event['longitude']; ?>])
                                        .addTo(map)
                                        .bindPopup("<?php echo htmlspecialchars($event['title']); ?>")
                                        .openPopup();
                                });
                            </script>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                Event not found.
            </div>
        <?php endif; ?>

    <?php else: ?>
        <?php if ($view_mode == 'list'): ?>
            <!-- List View -->
            <div class="space-y-6">
                <?php if (empty($events)): ?>
                    <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded">
                        No events found matching your criteria.
                    </div>
                <?php endif; ?>

                <?php foreach ($events as $event): ?>
                    <div
                        class="bg-white dark:bg-gray-800 rounded-lg shadow hover:shadow-md transition-shadow duration-300 overflow-hidden flex flex-col md:flex-row">
                        <?php if (!empty($event['image_url'])): ?>
                            <div class="md:w-1/4 h-48 md:h-auto">
                                <img src="<?php echo htmlspecialchars($event['image_url']); ?>"
                                    alt="<?php echo htmlspecialchars($event['title']); ?>" class="w-full h-full object-cover">
                            </div>
                        <?php endif; ?>
                        <div class="p-6 flex-1">
                            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-2">
                                <a href="?view=<?php echo $view_mode; ?>&event_id=<?php echo $event['id']; ?><?php echo !empty($search_query) ? '&search=' . urlencode($search_query) : '';
                                      echo $category_filter > 0 ? '&category=' . $category_filter : ''; ?>"
                                    class="hover:text-indigo-600 dark:hover:text-indigo-400">
                                    <?php echo htmlspecialchars($event['title']); ?>
                                </a>
                            </h2>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                                <span
                                    class="inline-block bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200 rounded-full px-3 py-1 text-sm font-semibold mr-2">
                                    <?php echo htmlspecialchars($event['category_name'] ?? 'Uncategorized'); ?>
                                </span>
                                <span>
                                    <?php echo date('F j, Y - g:i A', strtotime($event['event_date'])); ?>
                                </span>
                            </p>
                            <p class="text-gray-700 dark:text-gray-300 mb-4">
                                <?php echo substr(htmlspecialchars($event['description']), 0, 150) . (strlen($event['description']) > 150 ? '...' : ''); ?>
                            </p>
                            <div class="flex justify-between items-center">
                                <div class="text-gray-600 dark:text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1113.314-13.314 8 8 0 010 11.314z" />
                                    </svg>
                                    <?php echo htmlspecialchars($event['location']); ?>
                                </div>
                                <a href="?view=<?php echo $view_mode; ?>&event_id=<?php echo $event['id']; ?><?php echo !empty($search_query) ? '&search=' . urlencode($search_query) : '';
                                      echo $category_filter > 0 ? '&category=' . $category_filter : ''; ?>"
                                    class="text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300 font-medium">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

        <?php elseif ($view_mode == 'grid'): ?>
            <!-- Grid View -->
            <?php if (empty($events)): ?>
                <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded">
                    No events found matching your criteria.
                </div>
            <?php else: ?>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach ($events as $event): ?>
                        <div
                            class="bg-white dark:bg-gray-800 rounded-lg shadow hover:shadow-lg transition-shadow duration-300 overflow-hidden flex flex-col h-full">
                            <?php if (!empty($event['image_url'])): ?>
                                <div class="h-48 overflow-hidden">
                                    <img src="<?php echo htmlspecialchars($event['image_url']); ?>"
                                        alt="<?php echo htmlspecialchars($event['title']); ?>"
                                        class="w-full h-full object-cover transition-transform duration-300 hover:scale-105">
                                </div>
                            <?php endif; ?>
                            <div class="p-6 flex-1 flex flex-col">
                                <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-2">
                                    <a href="?view=<?php echo $view_mode; ?>&event_id=<?php echo $event['id']; ?><?php echo !empty($search_query) ? '&search=' . urlencode($search_query) : '';
                                          echo $category_filter > 0 ? '&category=' . $category_filter : ''; ?>"
                                        class="hover:text-indigo-600 dark:hover:text-indigo-400">
                                        <?php echo htmlspecialchars($event['title']); ?>
                                    </a>
                                </h2>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                                    <span
                                        class="inline-block bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200 rounded-full px-3 py-1 text-sm font-semibold mr-2">
                                        <?php echo htmlspecialchars($event['category_name'] ?? 'Uncategorized'); ?>
                                    </span>
                                    <span>
                                        <?php echo date('F j, Y', strtotime($event['event_date'])); ?>
                                    </span>
                                </p>
                                <p class="text-gray-700 dark:text-gray-300 mb-4 flex-1">
                                    <?php echo substr(htmlspecialchars($event['description']), 0, 100) . (strlen($event['description']) > 100 ? '...' : ''); ?>
                                </p>
                                <div class="flex justify-between items-center mt-auto">
                                    <div class="text-gray-600 dark:text-gray-400 text-sm truncate mr-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block mr-1" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1113.314-13.314 8 8 0 010 11.314z" />
                                        </svg>
                                        <?php echo htmlspecialchars($event['location']); ?>
                                    </div>
                                    <a href="?view=<?php echo $view_mode; ?>&event_id=<?php echo $event['id']; ?><?php echo !empty($search_query) ? '&search=' . urlencode($search_query) : '';
                                          echo $category_filter > 0 ? '&category=' . $category_filter : ''; ?>"
                                        class="text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300 font-medium">
                                        View
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

        <?php elseif ($view_mode == 'table'): ?>
            <!-- Table View -->
            <?php if (empty($events)): ?>
                <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded">
                    No events found matching your criteria.
                </div>
            <?php else: ?>
                <div class="overflow-x-auto bg-white dark:bg-gray-800 rounded-lg shadow">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Title
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Category
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Date & Time
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Location
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            <?php foreach ($events as $event): ?>
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            <?php echo htmlspecialchars($event['title']); ?>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="inline-block bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200 rounded-full px-3 py-1 text-xs font-semibold">
                                            <?php echo htmlspecialchars($event['category_name'] ?? 'Uncategorized'); ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-700 dark:text-gray-300">
                                            <?php echo date('M j, Y', strtotime($event['event_date'])); ?>
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            <?php echo date('g:i A', strtotime($event['event_date'])); ?>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-700 dark:text-gray-300">
                                            <?php echo htmlspecialchars($event['location']); ?>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="?view=<?php echo $view_mode; ?>&event_id=<?php echo $event['id']; ?><?php echo !empty($search_query) ? '&search=' . urlencode($search_query) : '';
                                              echo $category_filter > 0 ? '&category=' . $category_filter : ''; ?>"
                                            class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                            View Details
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>

        <?php elseif ($view_mode == 'map'): ?>
            <!-- Map View -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div id="mapView" class="h-96 w-full rounded-lg"></div>

                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        const map = L.map('mapView').setView([0, 0], 2);

                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                        }).addTo(map);

                        const events = <?php echo json_encode($events); ?>;
                        const markers = [];
                        const bounds = [];

                        events.forEach(event => {
                            if (event.latitude && event.longitude) {
                                const marker = L.marker([event.latitude, event.longitude])
                                    .addTo(map)
                                    .bindPopup(`
                                               <div class="text-center">
                                                   <h3 class="font-bold text-lg">${event.title}</h3>
                                                   <p class="text-sm mb-2">${new Date(event.event_date).toLocaleDateString()} - ${new Date(event.event_date).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}</p>
                                                   <a href="?view=<?php echo $view_mode; ?>&event_id=${event.id}<?php echo !empty($search_query) ? '&search=' . urlencode($search_query) : '';
                                                      echo $category_filter > 0 ? '&category=' . $category_filter : ''; ?>"
                                                      class="text-indigo-600 hover:underline">
                                                       View Details
                                                   </a>
                                               </div>
                                           `);
                                markers.push(marker);
                                bounds.push([event.latitude, event.longitude]);
                            }
                        });

                        if (bounds.length > 0) {
                            map.fitBounds(bounds);
                        }
                    });
                </script>

                <?php if (
                    empty($events) || !array_filter($events, function ($e) {
                    return !empty($e['latitude']) && !empty($e['longitude']);
                })
                ): ?>
                    <div class="mt-4 bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded">
                        No events with location data found matching your criteria.
                    </div>
                <?php endif; ?>
            </div>

        <?php elseif ($view_mode == 'calendar'): ?>
            <!-- Calendar View -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div id="calendarView" class="h-auto"></div>

                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        const calendarEl = document.getElementById('calendarView');
                        const calendar = new FullCalendar.Calendar(calendarEl, {
                            initialView: 'dayGridMonth',
                            headerToolbar: {
                                left: 'prev,next today',
                                center: 'title',
                                right: 'dayGridMonth,timeGridWeek,listWeek'
                            },
                            events: [
                                <?php foreach ($events as $event): ?>
                                                               {
                                        id: '<?php echo $event['id']; ?>',
                                        title: '<?php echo addslashes($event['title']); ?>',
                                        start: '<?php echo $event['event_date']; ?>',
                                        url: '?view=<?php echo $view_mode; ?>&event_id=<?php echo $event['id']; ?><?php echo !empty($search_query) ? '&search=' . urlencode($search_query) : '';
                                              echo $category_filter > 0 ? '&category=' . $category_filter : ''; ?>',
                                        extendedProps: {
                                            category: '<?php echo addslashes($event['category_name'] ?? 'Uncategorized'); ?>'
                                        },
                                        classNames: ['event-category-<?php echo $event['category_id'] ?? 0; ?>']
                                    },
                                <?php endforeach; ?>
                            ],
                            eventClick: function (info) {
                                info.jsEvent.preventDefault();
                                window.location.href = info.event.url;
                            }
                        });
                        calendar.render();
                    });
                </script>

                <?php if (empty($events)): ?>
                    <div class="mt-4 bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded">
                        No events found matching your criteria.
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</main>

<?php include 'includes/_footer.php' ?>