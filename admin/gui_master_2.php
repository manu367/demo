<?php
require_once("../includes/config.php");
set_exception_handler(function($e){
    if($e instanceof GlobalException){
        $errormsg=$e->getMessage();
        header("Location: gui_master.php?{$errormsg}");
        exit;
    }
});
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Responsive Layout</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        /* Smooth scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
        }
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        ::-webkit-scrollbar-thumb {
            background: rgba(100,100,100,0.4);
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: rgba(100,100,100,0.7);
        }
    </style>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/maps/12.6.0/highmaps.js"></script>
    <script src="https://code.highcharts.com/stock/12.6.0/highstock.js"></script>
    <script src="../js/gui_master.js"></script>
</head>
<body class="h-screen overflow-hidden bg-gray-100">

<!-- Mobile Header -->
<div class="md:hidden flex items-center justify-between p-4 bg-white shadow">
    <h1 class="text-lg font-semibold">App</h1>
    <button id="menuBtn" class="text-2xl">⋮</button>
</div>

<!-- Mobile Sidebar Overlay -->
<div id="mobileMenu" class="fixed inset-0 bg-black/40 opacity-0 pointer-events-none transition-opacity duration-300 z-50">
    <div id="sidebarPanel" class="w-64 bg-white h-full p-4 transform -translate-x-full transition-transform duration-300 ease-in-out shadow-xl">
        <button id="closeBtn" class="mb-4 text-xl">✕</button>
        <ul class="space-y-3">
            <li class="p-2 bg-gray-200 rounded">Dashboard</li>
            <li class="p-2 bg-gray-200 rounded">Profile</li>
            <li class="p-2 bg-gray-200 rounded">Settings</li>
        </ul>
    </div>
</div>

<!-- Main Layout -->
<div class="flex h-full">

    <!-- Main Content -->
    <!-- Main Content -->
    <div class="flex-1 overflow-y-auto p-4 md:p-6">
        <h2 class="text-2xl font-bold mb-6">Chart Panel</h2>

        <!-- Chart Card -->
        <div class="bg-white rounded-2xl shadow-md p-4 md:p-6 w-full max-w-5xl mx-auto">
            <div id="container" class="w-full h-[400px] md:h-[500px]"></div>
        </div>
    </div>

    <!-- Sidebar (Desktop) -->
    <aside class="hidden md:block w-80 bg-gray-800 h-full overflow-y-auto p-6 text-white shadow-xl">

        <h2 class="text-xl font-semibold mb-6 border-b border-gray-600 pb-2">
            Chart Config Panel
        </h2>

        <div class="space-y-5">

            <!-- Chart Type -->
            <div>
                <label class="block text-sm mb-1 text-gray-300">Chart Type</label>
                <select id="charttype" class="w-full p-2 rounded-lg bg-gray-700 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option>-- Select Chart Type --</option>
                </select>
            </div>

            <!-- Chart Title -->
            <div>
                <label class="block text-sm mb-1 text-gray-300">Chart Title</label>
                <input type="text" id="charttitle" placeholder="Enter chart title"
                       class="w-full p-2 rounded-lg bg-gray-700 border border-gray-600 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm mb-1 text-gray-300">X-Axis Label</label>
                <input type="text" id="x_axis_label" placeholder="Enter chart title"
                       class="w-full p-2 rounded-lg bg-gray-700 border border-gray-600 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm mb-1 text-gray-300">Y-Axis Label</label>
                <input type="text" id="y_axis_label" placeholder="Enter chart title"
                       class="w-full p-2 rounded-lg bg-gray-700 border border-gray-600 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Parameters -->
            <div>
                <label class="block text-sm mb-1 text-gray-300">X-Axis Parameter</label>
                <select id="x_axis_param" class="w-full p-2 rounded-lg bg-gray-700 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option>-- Select Parameter --</option>
                    <option>1</option>
                    <option>2</option>
                    <option>3</option>
                </select>
            </div>

            <div>
                <label class="block text-sm mb-1 text-gray-300">Y-Axis Parameter</label>
                <select id="y_axis_param" class="w-full p-2 rounded-lg bg-gray-700 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option>-- Select Parameter --</option>
                    <option>1</option>
                    <option>2</option>
                    <option>3</option>
                </select>
            </div>

            <!-- Divider -->
            <div class="border-t border-gray-600 pt-4 space-y-4">

                <!-- Alignment -->
                <div>
                    <label class="block text-sm mb-1 text-gray-300">Alignment</label>
                    <select id="chart_alignment" class="w-full p-2 rounded-lg bg-gray-700 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option>-- Select Alignment --</option>
                        <option>Left</option>
                        <option>Center</option>
                        <option>Right</option>
                    </select>
                </div>

                <!-- Color -->
                <div>
                    <label class="block text-sm mb-1 text-gray-300">Theme Color</label>
                    <input id="chart_color" type="color"
                           class="w-full h-10 p-1 rounded-lg bg-gray-700 border border-gray-600 cursor-pointer">
                </div>

            </div>

            <!-- Button -->
            <button class="w-full mt-4 bg-blue-600 hover:bg-blue-700 transition rounded-lg py-2 font-medium">
                Apply Changes
            </button>

        </div>

    </aside>

</div>

<script>
    const menuBtn = document.getElementById('menuBtn');
    const mobileMenu = document.getElementById('mobileMenu');
    const closeBtn = document.getElementById('closeBtn');
    const sidebarPanel = document.getElementById('sidebarPanel');

    function openMenu() {
        mobileMenu.classList.remove('pointer-events-none');
        mobileMenu.classList.remove('opacity-0');
        sidebarPanel.classList.remove('-translate-x-full');
    }

    function closeMenu() {
        mobileMenu.classList.add('opacity-0');
        sidebarPanel.classList.add('-translate-x-full');
        setTimeout(() => {
            mobileMenu.classList.add('pointer-events-none');
        }, 300);
    }

    menuBtn.addEventListener('click', openMenu);
    closeBtn.addEventListener('click', closeMenu);

    mobileMenu.addEventListener('click', (e) => {
        if (e.target === mobileMenu) {
            closeMenu();
        }
    });
</script>

</body>
</html>