<?php
$page_type = "insecure";
require_once("security/backend.php");

$arr_browsers = ["Firefox", "Chrome", "Safari", "Opera", "MSIE", "Trident", "Edge"];
$agent = $_SERVER['HTTP_USER_AGENT'];
$user_browser = '';
foreach ($arr_browsers as $browser) {
    if (strpos($agent, $browser) !== false) {
        $user_browser = $browser;
        break;
    }
}
switch ($user_browser) {
    case 'MSIE':
        $user_browser = 'Internet Explorer';
        break;
    case 'Trident':
        $user_browser = 'Internet Explorer';
        break;
    case 'Edge':
        $user_browser = 'Internet Explorer';
        break;
}
require_once("includes/common_function.php");
session_start();
if($_SESSION['userid']){
    if($_SESSION['id_type']=="admin"){
        header("Location:admin/home2.php");
        exit;
    }else if($_SESSION['id_type']=="WH"){
        header("Location:wh/home2.php");
        exit;
    }else{
        header("Location:asp/home2.php");
        exit;
    }
}
?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>CRM :: Support System</title>
        <!-- Tailwind -->
        <script src="https://cdn.tailwindcss.com"></script>
        <style>
            body {
                background: linear-gradient(135deg, #0f172a, #1e293b);
            }
        </style>
        <script>
            function chk_data() {
                const user = document.getElementById("userid");
                const pwd = document.getElementById("pwd");
                if (user.value.trim() === "") {
                    alert("Enter your email.");
                    user.focus();
                    return false;
                }
                if (pwd.value.trim() === "") {
                    alert("Enter your password.");
                    pwd.focus();
                    return false;
                }
                return true;
            }
        </script>
    </head>

    <body class="min-h-screen flex items-center justify-center text-gray-800">

    <div class="w-full max-w-md p-8 bg-white/95 backdrop-blur rounded-2xl shadow-2xl transition hover:scale-[1.01]">

        <!-- Logo -->
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900">CRM Login</h1>
            <p class="text-sm text-gray-500">Support System Access</p>
        </div>
        <!-- PHP Message -->
        <?php
        if(isset($_REQUEST["logres"]["msg"])) {
            $t_color = (isset($_REQUEST["logres"]["status"]) && $_REQUEST["logres"]["status"] == "success")
                    ? 'text-green-600' : 'text-red-600';
            echo '<div class="mb-4 text-center text-sm '.$t_color.'">'
                    .$_REQUEST["logres"]["msg"].'</div>';
            unset($_SESSION["logres"]["msg"]);
        }
        ?>
        <!-- Form -->
        <form method="post" action="verify.php" onsubmit="return chk_data()" class="space-y-5">
            <!-- Email -->
            <div>
                <input type="text" name="userid" id="userid" placeholder="Email Address"
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-black outline-none"
                />
            </div>

            <!-- Password -->
            <div>
                <input
                        type="password"
                        name="pwd"
                        id="pwd"
                        placeholder="Password"
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-black outline-none"
                />
            </div>

            <!-- Remember + Forgot -->
            <div class="flex items-center justify-between text-sm">
                <label class="flex items-center gap-2">
                    <input type="checkbox" class="accent-black" />
                    Remember me
                </label>

                <a href="#" class="text-gray-600 hover:underline">Forgot password?</a>
            </div>

            <!-- Button -->
            <button
                    type="submit"
                    class="w-full bg-black text-white py-3 rounded-lg hover:bg-gray-800 transition"
            >
                Sign In
            </button>

            <!-- Divider -->
            <div class="flex items-center gap-3 text-gray-400 text-sm">
                <div class="flex-1 h-px bg-gray-300"></div>
                OR
                <div class="flex-1 h-px bg-gray-300"></div>
            </div>

        </form>
    </div>
    <!-- Footer -->
    <div class="absolute bottom-4 text-gray-400 text-xs text-center w-full">
        © 2025 CRM System · Built for performance
    </div>
    </body>
    </html>
<?php //}?>