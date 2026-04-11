<?php
require_once("../security/dbh.php");
require_once ("formlogic.php");
global $link1;

if(isset($_REQUEST['formid'])){
    $formid = $_REQUEST['formid'];
    $data=loadform_1($link1,$formid);
    // data get from the db
    $paramName=json_decode($data['parameter_name']);
    $display_name=json_decode($data['display_name']);
    $type=json_decode($data['type']);
    $length=json_decode($data['length']);
    $param_require=json_decode($data['param_require']);
    $drop_down=json_decode($data['drop_down']);

    $basic_details=[];

    for($i=0;$i<count($paramName);$i++){
        $input_paramter=$paramName[$i];
        $input_display=$display_name[$i];
        $input_type=$type[$i];
        $input_length=$length[$i];
        $input_param_require=$param_require[$i];
        $input_drop_down=$drop_down[$i]??'0';
        $basic_details[]=new FormBaiscData($input_paramter,$input_display,$input_type,$input_length,$input_param_require,$input_drop_down);
    }

    $form_data=new FormViewSHow($data['id'],$data['form_name'],$data['fms_id'],$data['frm_seq'],$basic_details);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> <?=$data['form_name']?> Form</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4 font-sans">

<div class="w-full max-w-3xl bg-white rounded-2xl shadow-lg border border-gray-200">

    <!-- Header -->
    <div class="px-6 py-4 border-b">
        <h2 class="text-xl font-semibold text-gray-800">
            <?=$data['form_name']?> Form
        </h2>
    </div>

    <!-- Form -->
    <form action="publisformsubmit.php" enctype="multipart/form-data" method="post"  class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-5">
        <input type="hidden" name="fmsid" value="<?=$form_data->fms_id?>">
        <input type="hidden" name="formid" value="<?=$form_data->formid?>">
        <input type="hidden" name="pid" value="<?=$_REQUEST['pid']?>">
        <input type="hidden" name="hid" value="<?=$_REQUEST['hid']?>">

        <?=createForm($link1,$form_data)?>
        <!-- Button -->
        <div class="sm:col-span-2 pt-2">
            <button type="submit" name="save"
                    class="w-full bg-gray-900 text-white py-2.5 rounded-lg font-medium hover:bg-gray-800 active:scale-95 transition">
                Submit Form
            </button>
        </div>
    </form>

</div>

<!-- Styles -->
<style>
    .label {
        display: block;
        font-size: 13px;
        color: #374151;
        margin-bottom: 4px;
        font-weight: 500;
    }
    .input {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.2s ease;
    }
    .input:focus {
        border-color: #111827;
        box-shadow: 0 0 0 2px rgba(17,24,39,0.1);
        outline: none;
    }
</style>

<?php
if(isset($_REQUEST['msg']) && isset($_REQUEST['type'])){
    $type = $_REQUEST['type']; // success | error
    $msg = $_REQUEST['msg'];

    $isSuccess = ($type === 'success');
    ?>
    <div id="statusModal" class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">
        <div class="bg-white w-full max-w-sm rounded-2xl p-6 text-center shadow-xl animate-scaleIn">

            <!-- Icon -->
            <div class="w-16 h-16 mx-auto flex items-center justify-center rounded-full mb-4
            <?php echo $isSuccess ? 'bg-green-100' : 'bg-red-100'; ?>">

                <?php if($isSuccess){ ?>
                    <!-- Success Icon -->
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" stroke-width="3"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                <?php } else { ?>
                    <!-- Error Icon -->
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" stroke-width="3"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                <?php } ?>
            </div>

            <!-- Title -->
            <h3 class="text-lg font-semibold text-gray-800">
                <?php echo $isSuccess ? 'Success' : 'Error'; ?>
            </h3>

            <!-- Message -->
            <p class="text-sm text-gray-500 mt-1">
                <?php echo htmlspecialchars($msg); ?>
            </p>

            <!-- Button -->
            <button onclick="closeModal()"
                    class="mt-5 w-full py-2 rounded-lg text-white transition
                <?php echo $isSuccess ? 'bg-green-600 hover:bg-green-700' : 'bg-red-600 hover:bg-red-700'; ?>">
                OK
            </button>

        </div>
    </div>

    <style>
        @keyframes scaleIn {
            from { transform: scale(0.8); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }
        .animate-scaleIn {
            animation: scaleIn 0.25s ease;
        }
    </style>

    <script>
        function closeModal() {
            document.getElementById("statusModal").style.display = "none";
            if (window.history.replaceState) {
                const url = new URL(window.location);
                url.searchParams.delete('msg');
                url.searchParams.delete('type');
                window.history.replaceState({}, document.title, url.pathname + url.search);
            }
        }
    </script>

<?php } ?>


</body>
</html>