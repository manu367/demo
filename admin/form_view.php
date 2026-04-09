<?php
/*
 * Page Overview:
 * Task 1:
 * This page retrieves form details from the `form_master` table using the FormID.
 *  Based on the fetched configuration, dynamic input fields are generated and displayed.
 *  The received inputs are then processed and added accordingly.
 *
 * Task 2:
 * The generated input fields are assigned appropriate values.
 * Validation is applied to ensure data accuracy and integrity before further processing.
 *
 */

function fileUpload($field){
    if(isset($_FILES[$field]) && $_FILES[$field]['error'] == 0){
        $fileName = $_FILES[$field]['name'];
        $tmpName  = $_FILES[$field]['tmp_name'];
        $fileSize = $_FILES[$field]['size'];

        // File size limit (6MB)
        if($fileSize > 6 * 1024 * 1024){
            die("File too large");
        }

        // Current date folder (YYYY-MM-DD)
        $dateFolder = date("Y-m-d");
        $uploadDir = "../upload_fms_file/" . $dateFolder . "/";

        // Create folder if not exists
        if(!is_dir($uploadDir)){
            mkdir($uploadDir, 0777, true);
        }

        // Unique file name
        $newName = time() . "_" . basename($fileName);
        $destination = $uploadDir . $newName;

        if(move_uploaded_file($tmpName, $destination)){
            return $destination;
        }
    }
    return false;
}

//function fileUpload($field){
//    if(isset($_FILES[$field]) && $_FILES[$field]['error'] == 0){
//        $fileName = $_FILES[$field]['name'];
//        $tmpName  = $_FILES[$field]['tmp_name'];
//        $fileSize = $_FILES[$field]['size'];
//        if($fileSize > 6 * 1024 * 1024){
//            die("File too large");
//        }
//        $uploadDir = "../upload_fms_file/";
//        $newName = time() . "_" . basename($fileName);
//        $destination = $uploadDir . $newName;
//        if(move_uploaded_file($tmpName, $destination)){
//            return $uploadDir.$newName;
//        }
//    }
//    return false;
//}

require_once("../includes/config.php");
global $link1;

$formview=new FormView($link1);
$data=[];
if(isset($_REQUEST['formid'])){
    $formid = base64_decode($_REQUEST['formid']);
    $data=$formview->loadform($formid);
    $fms_de=fmsloading($link1,$data['fms_id']);
}


if(isset($_POST['save'])){
    var_dump($_POST);exit();
    $data=$formview->loadform($formid);
    $total=count(json_decode($data['parameter_name']));
    $parameter[]=json_decode($data['parameter_name'],true);

    $parameter_1=$parameter[0];
    $data_save=[];
    for($i=0;$i<$total;$i++){
        $fieldName = $parameter_1[$i];
        $uploadedFile = fileUpload($fieldName);
        if($uploadedFile !== false){
            $data_save[] = $uploadedFile;
        } else {
            $data_save[] = $_POST[$fieldName] ?? '';
        }
    }

    $parameter_1[]="updated_by";
    $parameter_1[]="updated_ip";

    $data_save[]=$_SESSION['userid'];
    $data_save[]=$_SERVER['REMOTE_ADDR'];

    $save=$formview->saveDataintable($_POST['table_name'],$parameter_1,$data_save);

    if($save){
        operationtracker($link1,$_SESSION['userid'],'form_view',"Add form data",'ADD',$_SERVER['REMOTE_ADDR']);
        $tablename=$_POST['table_name'];
        header("location:fms_view.php?msg=saved data successfully in $tablename table");
        exit();
    }
    else{
        throw new GlobalException("Data is not saved");
    }
}

$isPermission=PermissionManager::checkaddRights($link1,$_SESSION['userid'],$_REQUEST['pid']);

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?=siteTitle?></title>
    <meta http-equiv="refresh" content="1800">
    <link rel="shortcut icon" href="../images/titleimg.png" type="image/png">
    <script src="../js/jquery.js"></script>
    <link href="../css/font-awesome.min.css" rel="stylesheet">
    <link href="../css/abc.css" rel="stylesheet">
    <script src="../js/bootstrap.min.js"></script>
    <link href="../css/abc2.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/bootstrap.min.css">

    <script type="text/javascript" src="../js/jquery.validate.js"></script>
    <script type="text/javascript" src="../js/bootstrap-multiselect.js"></script>
    <link rel="stylesheet" href="../css/bootstrap-multiselect.css" type="text/css"/>
    <style>
        #previewModal {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.75);
            backdrop-filter: blur(8px);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 999;
            padding: 10px; /* prevents edge sticking on small screens */
        }
        #previewImage,
        #pdfPreview {
            max-width: 100%;
            max-height: 100%;
        }

        .modal-content {
            width: 100%;
            max-width: 900px;
            height: 90vh; /* responsive height */
            background: #fff;
            border-radius: 10px;
            padding: 15px;
            display: flex;
            flex-direction: column;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        #closeBtn {
            font-size: 22px;
            cursor: pointer;
        }

        .modal-body {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
            position: relative; /* important */
        }

        #previewImage {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }
        /* Tablets */
        @media (max-width: 768px) {
            .modal-content {
                height: 85vh;
                padding: 12px;
            }

            #closeBtn {
                font-size: 20px;
            }
        }

        #pdfPreview {
            width: 100%;
            height: 100%;
            border: none;
        }
        /* Mobile */
        @media (max-width: 480px) {
            .modal-content {
                height: 80vh;
                padding: 10px;
                border-radius: 8px;
            }

            .modal-header {
                font-size: 14px;
            }

            #closeBtn {
                font-size: 18px;
            }
        }
        .hidden{
            display: none;
        }
    </style>
    <link href="https://unpkg.com/cropperjs/dist/cropper.min.css" rel="stylesheet"/>
    <script src="https://unpkg.com/cropperjs"></script>
</head>
<body>

<div class="container-fluid">
    <div class="row content">
        <?php
        include("../includes/leftnav2.php");
        ?>
        <div class="<?=$screenwidth?>">
            <h2 align="center"><i class="fa fa-users"></i> Form View </h2><br/><br/>
            <div class="form-group"  id="page-wrap" style="margin-left:10px;" >
                <?php
                if($isPermission){
                ?>
                <form  name="frm1" id="frm1" class="form-horizontal" action="" method="post" enctype="multipart/form-data">
                    <input name="fmsid" value="<?=$fms_de['id']?>" type="hidden"/>
                    <input name="fmsname" value="<?=$fms_de['fmsname']?>" type="hidden"/>
                    <input name="table_name" value="<?=$fms_de['table_name']?>" type="hidden"/>
                    <input name="formid_value_h" value="<?=$data['id']?>" type="hidden"/>
                    <?php
                    $formview->viewFrom($data);
                    ?>

                    <div class="text-center mt-5">
                        <button type="submit" name="save" class="btn btn-primary">Add</button>
                        <span  class="btn btn-primary" onclick="window.location.href='fms_view.php?pid=292&hid=Masters'"><span id="operation_name">Cancel</span</span>
                    </div>
                </form>
                <?php }else{
                ?>
                    <div class="d-flex justify-content-center align-items-center" style="height:70vh;">
                        <div class="card shadow-lg text-center" style="max-width: 420px; border-radius:15px;">
                            <div class="card-body">
                                <div style="font-size:60px; color:#dc3545;">
                                    <i class="fa fa-lock"></i>
                                </div>
                                <h3 class="mt-3" style="font-weight:600;">Access Denied</h3>
                                <p class="text-muted mt-2">
                                    You don’t have permission to access this page.<br>
                                    Please contact your administrator if you believe this is a mistake.
                                </p>

                                <div class="mt-4">
                                    <a href="fms_view.php?pid=<?=$_REQUEST['pid']?>&hid=<?=$_REQUEST['hid']?>" class="btn btn-primary">
                                        <i class="fa fa-arrow-left"></i> Go Back
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
</div>


<!--image preview-->
<div id="previewModal" class="hidden">
    <div class="modal-content">

        <div class="modal-header">
            <div class="modal-title">Image Preview</div>
        </div>

        <div class="modal-body">
            <img id="previewImage" src="" />
            <embed  id="pdfPreview" class="hidden"/>
        </div>

        <button id="done" class="btn btn-primary">Done</button>

    </div>
</div>

<?php
include("../includes/footer.php");
include("../includes/connection_close.php");
?>
<script>
    document.querySelectorAll("label").forEach((label)=>{
        label.style.textTransform="capitalize"
    });
    document.querySelectorAll("input").forEach((label)=>{
        label.style.textTransform="uppercase"
    });

    const input = document.querySelector('input[type="file"]');
    const modal = document.getElementById("previewModal");
    const img = document.getElementById("previewImage");
    const pdfFrame = document.getElementById("pdfPreview");
    const closeBtn = document.getElementById("closeBtn");
    const done = document.getElementById("done");

    input.addEventListener("change", (e) => {
        const file = e.target.files[0];

        if (!file) return;

        // check PNG only
        if (file.type === "image/png") {
            const reader = new FileReader();
            reader.onload = function (event) {
                img.classList.remove("hidden");
                pdfFrame.classList.add("hidden");
                img.src = event.target.result;
                modal.style.display = "";
            };

            reader.readAsDataURL(file);
            modal.classList.remove("hidden");
        }
        else if (file.type === "application/pdf"){
            const fileURL = URL.createObjectURL(file);
            pdfFrame.src = fileURL + "#toolbar=1";
            // pdfFrame.src = fileURL;
            pdfFrame.classList.remove("hidden");
            img.classList.add("hidden");
            modal.classList.remove("hidden");
        }
        else {
            alert("Allow only PDF and PNG File");
        }
    });

    done.addEventListener("click", () => {
        modal.classList.add("hidden");
    });
</script>
</body>
</html>