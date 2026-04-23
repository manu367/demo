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
                <select class="w-full p-2 rounded-lg bg-gray-700 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option>-- Select Chart Type --</option>
                    <option>Bar Chart</option>
                    <option>Line Chart</option>
                    <option>Pie Chart</option>
                    <option>Area Chart</option>
                    <option>Scatter Plot</option>
                </select>
            </div>

            <!-- Chart Title -->
            <div>
                <label class="block text-sm mb-1 text-gray-300">Chart Title</label>
                <input type="text" placeholder="Enter chart title"
                       class="w-full p-2 rounded-lg bg-gray-700 border border-gray-600 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Parameters -->
            <div>
                <label class="block text-sm mb-1 text-gray-300">X-Axis Parameter</label>
                <select class="w-full p-2 rounded-lg bg-gray-700 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option>-- Select Parameter --</option>
                    <option>1</option>
                    <option>2</option>
                    <option>3</option>
                </select>
            </div>

            <div>
                <label class="block text-sm mb-1 text-gray-300">Y-Axis Parameter</label>
                <select class="w-full p-2 rounded-lg bg-gray-700 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
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
                    <select class="w-full p-2 rounded-lg bg-gray-700 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option>-- Select Alignment --</option>
                        <option>Left</option>
                        <option>Center</option>
                        <option>Right</option>
                    </select>
                </div>

                <!-- Color -->
                <div>
                    <label class="block text-sm mb-1 text-gray-300">Theme Color</label>
                    <input type="color"
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
<script>
    // Data retrieved from https://fas.org/issues/nuclear-weapons/status-world-nuclear-forces/
    Highcharts.chart('container', {
        chart: {
            type: 'area'
        },
        accessibility: {
            description: 'Image description: An area chart compares the nuclear ' +
                'stockpiles of the USA and the USSR/Russia between 1945 and ' +
                '2024. The number of nuclear weapons is plotted on the Y-axis ' +
                'and the years on the X-axis. The chart is interactive, and the ' +
                'year-on-year stockpile levels can be traced for each country. ' +
                'The US has a stockpile of 2 nuclear weapons at the dawn of the ' +
                'nuclear age in 1945. This number has gradually increased to 170 ' +
                'by 1949 when the USSR enters the arms race with one weapon. At ' +
                'this point, the US starts to rapidly build its stockpile ' +
                'culminating in 31,255 warheads by 1966 compared to the USSR’s 8,' +
                '400. From this peak in 1967, the US stockpile gradually ' +
                'decreases as the USSR’s stockpile expands. By 1978 the USSR has ' +
                'closed the nuclear gap at 25,393. The USSR stockpile continues ' +
                'to grow until it reaches a peak of 40,159 in 1986 compared to ' +
                'the US arsenal of 24,401. From 1986, the nuclear stockpiles of ' +
                'both countries start to fall. By 2000, the numbers have fallen ' +
                'to 10,577 and 12,188 for the US and Russia, respectively. The ' +
                'decreases continue slowly after plateauing in the 2010s, and in ' +
                '2024 the US has 3,708 weapons compared to Russia’s 4,380.'
        },
        title: {
            text: 'US and USSR nuclear stockpiles'
        },
        subtitle: {
            text: 'Source: <a href="https://fas.org/issues/nuclear-weapons/status-world-nuclear-forces/" ' +
                'target="_blank">FAS</a>'
        },
        xAxis: {
            allowDecimals: false,
            accessibility: {
                rangeDescription: 'Range: 1940 to 2024.'
            }
        },
        yAxis: {
            title: {
                text: 'Nuclear weapon states'
            }
        },
        tooltip: {
            pointFormat: '{series.name} had stockpiled <b>{point.y:,.0f}</b><br/>' +
                'warheads in {point.x}'
        },
        plotOptions: {
            area: {
                pointStart: 1940,
                marker: {
                    enabled: false,
                    symbol: 'circle',
                    radius: 2,
                    states: {
                        hover: {
                            enabled: true
                        }
                    }
                }
            }
        },
        series: [{
            name: 'USA',
            data: [
                null, null, null, null, null, 2, 9, 13, 50, 170, 299, 438, 841,
                1169, 1703, 2422, 3692, 5543, 7345, 12298, 18638, 22229, 25540,
                28133, 29463, 31139, 31175, 31255, 29561, 27552, 26008, 25830,
                26516, 27835, 28537, 27519, 25914, 25542, 24418, 24138, 24104,
                23208, 22886, 23305, 23459, 23368, 23317, 23575, 23205, 22217,
                21392, 19008, 13708, 11511, 10979, 10904, 11011, 10903, 10732,
                10685, 10577, 10526, 10457, 10027, 8570, 8360, 7853, 5709, 5273,
                5113, 5066, 4897, 4881, 4804, 4717, 4571, 4018, 3822, 3785, 3805,
                3750, 3708, 3708, 3708, 3708
            ]
        }, {
            name: 'USSR/Russia',
            data: [
                null, null, null, null, null, null, null, null, null,
                1, 5, 25, 50, 120, 150, 200, 426, 660, 863, 1048, 1627, 2492,
                3346, 4259, 5242, 6144, 7091, 8400, 9490, 10671, 11736, 13279,
                14600, 15878, 17286, 19235, 22165, 24281, 26169, 28258, 30665,
                32146, 33486, 35130, 36825, 38582, 40159, 38107, 36538, 35078,
                32980, 29154, 26734, 24403, 21339, 18179, 15942, 15442, 14368,
                13188, 12188, 11152, 10114, 9076, 8038, 7000, 6643, 6286, 5929,
                5527, 5215, 4858, 4750, 4650, 4600, 4500, 4490, 4300, 4350, 4330,
                4310, 4495, 4477, 4489, 4380
            ]
        }]
    });

</script>

</body>
</html>