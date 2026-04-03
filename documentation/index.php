<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Documentation</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 text-gray-800">

<!-- Navbar -->
<header class="bg-white shadow-md sticky top-0 z-50">
    <div class="max-w-6xl mx-auto px-4 py-3 flex justify-between items-center">
        <h1 class="text-xl font-bold">FMS </h1>
        <nav class="hidden md:flex gap-6">
<!--            <a href="#intro" class="hover:text-blue-600">Intro</a>-->
<!--            <a href="#features" class="hover:text-blue-600">Features</a>-->
<!--            <a href="#setup" class="hover:text-blue-600">Setup</a>-->
<!--            <a href="#usage" class="hover:text-blue-600">Usage</a>-->
        </nav>
    </div>
</header>


<!-- Main Content -->
<main class="max-w-6xl mx-auto px-4 py-8 grid md:grid-cols-4 gap-6">

    <!-- Sidebar -->
    <aside class="md:col-span-1 bg-white p-4 rounded-xl shadow">
        <h3 class="font-semibold mb-3">Contents</h3>
        <ul class="space-y-2 text-sm">

            <!-- Introduction -->
            <li>
                <button class="w-full text-left hover:text-blue-600 font-medium">
                    Introduction
                </button>
                <ul id="introMenu" class="ml-4 mt-1 space-y-1">
                    <li><a href="#intro" class="block hover:text-blue-500">Overview</a></li>
                    <li><a href="#intro" class="block hover:text-blue-500">Purpose</a></li>
                </ul>
            </li>

            <!-- login Page -->
            <li>
                <button class="w-full text-left hover:text-blue-600 font-medium">
                    Login Page
                </button>
                <ul id="featuresMenu" class="ml-4 mt-1 space-y-1">
                    <li><a href="#features" class="block hover:text-blue-500">Structure</a></li>
                    <li><a href="#features" class="block hover:text-blue-500">Session Re-creation</a></li>
                </ul>
            </li>

            <!-- Admin User -->
            <li>
                <button class="w-full text-left hover:text-blue-600 font-medium">
                    Admin User
                </button>
                <ul id="setupMenu" class="ml-4 mt-1 space-y-1">
                    <li><a href="#setup" class="block hover:text-blue-500">Structure of  Page</a></li>
                    <li><a href="#setup" class="block hover:text-blue-500">Add User</a></li>
                    <li><a href="#setup" class="block hover:text-blue-500">Bulk Upload</a></li>
                    <li><a href="#setup" class="block hover:text-blue-500">Update</a></li>
                    <li><a href="#setup" class="block hover:text-blue-500">Update Permission</a></li>
                    <li><a href="#setup" class="block hover:text-blue-500">Update Permission - Master Reports</a></li>
                    <li><a href="#setup" class="block hover:text-blue-500">Update Permission - FMS</a></li>
                </ul>
            </li>

            <!-- FMS Master -->
            <li>
                <button class="w-full text-left hover:text-blue-600 font-medium">
                    FMS Master
                </button>
                <ul id="fmsmaster" class="ml-4 mt-1 space-y-1">
                    <li><a href="#fmsmaster_structure" class="block hover:text-blue-500">Structure</a></li>
                    <li><a href="#fmsmaster_add" class="block hover:text-blue-500">Add FMS Maste</a></li>
                    <li><a href="#fmsmaster_view" class="block hover:text-blue-500">View FMS Maste</a></li>
                    <li><a href="#fmsmaster_clone" class="block hover:text-blue-500">Clone FMS Maste</a></li>
                </ul>
            </li>

            <!-- Form Master -->
            <li>
                <button class="w-full text-left hover:text-blue-600 font-medium">
                    Form Master
                </button>
                <ul id="formaMaster" class="ml-4 mt-1 space-y-1">
                    <li><a href="#formaMaster_structure" class="block hover:text-blue-500">Structure</a></li>
                    <li><a href="#formaMaster_add" class="block hover:text-blue-500">Add Form Maste</a></li>
                    <li><a href="#formaMaster_view" class="block hover:text-blue-500">View Form Maste</a></li>
                    <li><a href="#formaMaster_update" class="block hover:text-blue-500">Update form Maste</a></li>
                </ul>
            </li>

<!--            FMS View-->
            <li>
                <button class="w-full text-left hover:text-blue-600 font-medium">
                    FMS View
                </button>
                <ul id="fmsview" class="ml-4 mt-1 space-y-1">
                    <li><a href="#fmsview_structure" class="block hover:text-blue-500">Structure</a></li>
                    <li><a href="#fmsview_view" class="block hover:text-blue-500">Fms View</a></li>
                    <li><a href="#fmsview_upload" class="block hover:text-blue-500">Fms Upload</a></li>
                    <li><a href="#fmsview_api" class="block hover:text-blue-500">Fms API</a></li>
                    <li><a href="#fmsview_reports" class="block hover:text-blue-500">FMS Reports</a></li>
                </ul>
            </li>

<!--            Role Master-->
            <li>
                <button class="w-full text-left hover:text-blue-600 font-medium">
                    Role Master
                </button>
                <ul id="formaMaster" class="ml-4 mt-1 space-y-1">
                    <li><a href="#role_structure" class="block hover:text-blue-500">Structure</a></li>
                    <li><a href="#role_add" class="block hover:text-blue-500">Add Role</a></li>
                    <li><a href="#role_edit" class="block hover:text-blue-500">Edit Role</a></li>
                    <li><a href="#role_permissionmapping" class="block hover:text-blue-500">Permission Mapping</a></li>
                </ul>
            </li>

        </ul>
    </aside>

    <!-- Content -->
    <section class="md:col-span-3 space-y-6">

        <!-- Introduction -->
        <div id="intro" class="bg-white p-6 rounded-xl shadow">
            <h2 class="text-2xl font-bold mb-3">Introduction</h2>
            <p class="text-sm leading-relaxed">
                This project is designed to provide a clean and efficient solution.
                It focuses on simplicity, performance, and usability.
            </p>
        </div>

        <!-- login Page -->
        <div id="features" class="bg-white p-6 rounded-xl shadow">
            <h2 class="text-2xl font-bold mb-3">Login Page</h2>
            <ul class="list-disc pl-5 text-sm space-y-2">
                <li>Responsive Design (Mobile Friendly)</li>
                <li>Modern UI with Tailwind CSS</li>
                <li>Easy to Customize</li>
                <li>Clean Layout</li>
            </ul>
        </div>

        <!-- Admin User  -->
        <div id="setup" class="bg-white p-6 rounded-xl shadow">
            <h2 class="text-2xl font-bold mb-3">Installation</h2>
            <pre class="bg-gray-900 text-green-400 text-sm p-4 rounded-lg overflow-x-auto">
git clone https://github.com/your-repo/project.git
cd project
npm install
npm run dev
        </pre>
        </div>

        <!-- FMS Master -->
        <div id="usage" class="bg-white p-6 rounded-xl shadow">
            <h2 class="text-2xl font-bold mb-3">Usage</h2>
            <p class="text-sm">
                After installation, run the project and open it in your browser.
                Customize components as needed.
            </p>
        </div>

        <!-- Form Master -->
        <div id="usage" class="bg-white p-6 rounded-xl shadow">
            <h2 class="text-2xl font-bold mb-3">Usage</h2>
            <p class="text-sm">
                After installation, run the project and open it in your browser.
                Customize components as needed.
            </p>
        </div>

        <!-- FMS View -->
        <div id="usage" class="bg-white p-6 rounded-xl shadow">
            <h2 class="text-2xl font-bold mb-3">Usage</h2>
            <p class="text-sm">
                After installation, run the project and open it in your browser.
                Customize components as needed.
            </p>
        </div>

        <!-- Role Master -->
        <div id="role_structure" class="bg-white p-6 rounded-xl shadow">
            <h2 class="text-2xl font-bold mb-3">Usage</h2>
            <p class="text-sm">
                After installation, run the project and open it in your browser.
                Customize components as needed.
            </p>
        </div>
        <div id="role_add" class="bg-white p-6 rounded-xl shadow">
            <h2 class="text-2xl font-bold mb-3">Usage</h2>
            <p class="text-sm">
                After installation, run the project and open it in your browser.
                Customize components as needed.
            </p>
        </div>
        <div id="role_edit" class="bg-white p-6 rounded-xl shadow">
            <h2 class="text-2xl font-bold mb-3">Usage</h2>
            <p class="text-sm">
                After installation, run the project and open it in your browser.
                Customize components as needed.
            </p>
        </div>
        <div id="role_permissionmapping" class="bg-white p-6 rounded-xl shadow">
            <h2 class="text-2xl font-bold mb-3">Usage</h2>
            <p class="text-sm">
                After installation, run the project and open it in your browser.
                Customize components as needed.
            </p>
        </div>

    </section>

</main>

<!-- Footer -->
<footer class="bg-white text-center text-sm py-4 mt-8 shadow-inner">
    © 2026 Your Project. All rights reserved.
</footer>
<script>
    function toggleMenu(id) {
        const menu = document.getElementById(id);
        menu.classList.toggle("hidden");
    }
</script>
</body>
</html>