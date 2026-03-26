<?php
require_once("../includes/config.php");

set_exception_handler(function($e){
    if($e instanceof GlobalException){
        $msg = urlencode($e->getMessage());
        header("Location: fms_master.php?msg={$msg}");
        exit;
    }
});

$fsm_id=base64_decode($_REQUEST['id']);
function loadFSM($link,$sql){
    $result=null;
    try{
        $result=mysqli_query($link,$sql);
    }catch (Exception $e){
        throw new GlobalException("Error loading data: ".$e->getMessage());
    }
    if($result){
        $row=mysqli_fetch_assoc($result);
        return $row;
    }
    return false;
}

$load=loadFSM($link1,"SELECT * FROM fms_master where id=$fsm_id");

if($load){
//    var_dump($load);
}else{
    throw new GlobalException("Id is Not valid");
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="../images/titleimg.png" type="image/png">
    <link href="../css/font-awesome.min.css" rel="stylesheet">
    <link href="../css/abc.css" rel="stylesheet">
    <script src="../js/jquery.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <link href="../css/abc2.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/jquery.dataTables.min.css">
    <script type="text/javascript" src="../js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function(){
            $("#frm1").validate();
        });
    </script>
    <script type="text/javascript" src="../js/jquery.validate.js"></script>
    <!-- Include Date Picker -->
    <script type="text/javascript" src="../js/bootstrap-multiselect.js"></script>
    <link rel="stylesheet" href="../css/bootstrap-multiselect.css" type="text/css"/>
    <script>
        $(document).ready(function () {
            $('#myacc-users-grid').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "../pagination/form-data-grid-view.php",
                    type: "POST",
                    data: {
                        pid: "<?= $_REQUEST['pid'] ?? '' ?>",
                        hid: "<?= $_REQUEST['hid'] ?? '' ?>",
                        id:"<?=$load['id']?>"
                    },
                    error: function () {
                        $("#myacc-users-grid").append(
                            '<tbody><tr><td colspan="10">No data found</td></tr></tbody>'
                        );
                        // $("#myacc-users-grid_processing").hide();
                    }
                }
            });
        });
    </script>
    <style>

        /* Full screen panel */
        .sheet {
            position: fixed;
            bottom: -100%;
            left: 0;
            width: 100%;
            height: 100%;
            background: #f9f9f9;
            transition: 0.4s ease;
            z-index: 999;
            display: flex;
            flex-direction: column;
        }

        /* Active state */
        .sheet.active {
            bottom: 0;
        }

        /* Top close button */
        .close {
            position: absolute;
            top: 15px;
            right: 20px;
            font-size: 20px;
            z-index: 10;
        }

        /* Scrollable content */
        .content_api {
            padding: 60px 20px 80px;
            overflow-y: auto;
            flex: 1;
        }

        /* Cards */
        .card {
            background: #fff;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        /* Header row */

        /* Code block */
        .code {
            background: #111;
            color: #0f0;
            padding: 10px;
            margin-top: 10px;
            border-radius: 6px;
            font-size: 13px;
            overflow-x: auto;
            white-space: pre-wrap;
        }

        /* Buttons */
        .btn {
            padding: 5px 10px;
            cursor: pointer;
        }

        .ok {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            padding: 10px 20px;
        }

        /* Badges */
        .badge {
            padding: 3px 8px;
            border-radius: 5px;
            color: #fff;
            font-size: 12px;
        }

        .get {
            background: green;
        }

        .post {
            background: orange;
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row content">
        <?php include("../includes/leftnav2.php"); ?>
        <div class="<?=$screenwidth?>">
            <h2 align="center"><i class="fa fa-users"></i> Form View</h2><br/><br/>

            <div class="form-group"  id="page-wrap" style="margin-left:10px;" >
                <div class="form-group">
                    <div class="col-md-6">
                        <label for="user_id" class="col-md-6 control-label">
                            FMS Name<span class="red_small">*</span>
                        </label>
                        <div class="col-md-6">
                            <input name="fmsname" type="text" class="form-control" disabled
                                   value="<?= $load['fmsname']!==null?$load['fmsname']:''?>"
                                <?= $is_edit ? '' : '' ?> required>

                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="username" class="col-md-6 control-label">Details<span class="red_small">*</span></label>
                        <div class="col-md-6">
                            <input name="fms_details" type="text" class="form-control" disabled
                                   value="<?= $load['details']!==null?$load['details']:''?>"
                                   required>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-center mt-5">
            </div>

            <div class="mt-5 text-right" style="margin-top: 100px;margin-bottom: 10px">

            </div>

            <table width="100%" id="myacc-users-grid" class="display table table-hover" style="margin-top:4px;" align="center" cellpadding="4" cellspacing="0" border="1">
                <thead class="bg-primary">
                <tr>
                    <td>#</td>
                    <td>Form Name</td>
                    <td>Display Name</td>
                    <td>Status</td>
                    <td>View</td>
                    <td>Upload</td>
                    <td>API</td>
                </tr>
                </thead>
            </table>

            <div class="text-center">
                <span class="btn btn-primary" onclick="window.location.href='fms_view.php?pid=290&hid=Masters'" style="text-transform: capitalize"><span id="operation_name">back</span></span>
            </div>
        </div>
    </div>
</div>
</div>

<section id="api_view" class="sheet">
    <button class="close" onclick="closeSheet()">X</button>

    <div class="content_api">

        <div class="card">
            <div class="header">
                <h2>Get Form Fields <span class="badge get">GET</span></h2>
                <button class="btn" onclick="copyText('getReq')">Copy</button>
            </div>

            <div class="code" id="getReq">
                curl --location 'http://localhost/demo/fmsapi/fmsconnect.php'
                --header 'fms_id: <?=$load['id']?>'
                --header 'fms_name: <?=$load['fmsname']?>'
                --header 'form_id: <span id="form_id">43</span>'
                --header 'form_name: <span id="form_name">feedback form</span>'
                --header 'Content-Type: application/json'
                --header 'Authorization: Basic dGVzdDoxMjM='
                --data-raw '{
                <p id="data_raw"></p>
                }'
            </div>
        </div>

    </div>

    <button id="ok" class="ok" onclick="closeSheet()">OK</button>
</section>

<?php
include("../includes/footer.php");
include("../includes/connection_close.php");
?>
<script>
    const okbutton=document.getElementById("ok");
    const  data_raw_element=document.getElementById("data_raw");
    const form_id_element=document.getElementById("form_id");
    const  form_name_element=document.getElementById("form_name");

    okbutton.style.display = "none";
    function showApi(el){


        const formId = el.dataset.fromid;
        const formName = el.dataset.formname;
        const column = el.dataset.column.split('-');
        console.log("Form ID:", formId);
        form_id_element.innerHTML = formId;
        form_name_element.innerHTML = formName;
        column.forEach(element => {
            data_raw_element.innerHTML +=`${element} : ' ' ,<br/>`;
        })
        // data_raw_element.innerHTML = data_row;?
        openSheet();
    }
    function openSheet() {
        document.getElementById("api_view").classList.add("active");
        okbutton.style.display = "block";
    }

    function closeSheet() {
        document.getElementById("api_view").classList.remove("active");
        okbutton.style.display = "none";
        data_raw_element.innerHTML="";
        form_id_element.innerHTML="";
        form_name_element.innerHTML="";
    }
    function copyText(id) {
        const text = document.getElementById(id).innerText;
        navigator.clipboard.writeText(text);
        alert("Copied!");
    }
</script>
</body>
</html>