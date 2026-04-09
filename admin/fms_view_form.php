<?php
require_once("../includes/config.php");

set_exception_handler(function($e){
    if($e instanceof GlobalException){
        $msg = urlencode($e->getMessage());
        header("Location: fms_master.php?msg={$msg}");
        exit;
    }
});

if (!isset($_REQUEST['id']) || empty($_REQUEST['id'])) {
    throw new GlobalException("Id is not valid");
}

// here we are getting the FMS_ID and load basic details from the fms_master
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
    <script type="text/javascript" src="../js/bootstrap-multiselect.js"></script>
    <link rel="stylesheet" href="../css/bootstrap-multiselect.css" type="text/css"/>

    <link rel="stylesheet" href="../css/dataTables.responsive.css">
    <script type="text/javascript" src="../js/dataTables.responsive.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#myacc-users-grid').DataTable({
                "responsive": true,
                "processing": true,
                "serverSide": true,
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
            font-size: 18px;
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
    <style>
        /* QR alignment */
        #qrcode img,
        #qrcode canvas {
            display: block;
            margin: 0 auto;
        }

        /* Side Sheet Base */
        #sideSheet {
            position: fixed;
            top: 0;
            right: 0;
            width: 420px;
            max-width: 100%;
            height: 100%;
            background: #fff;
            box-shadow: -10px 0 30px rgba(0,0,0,0.15);

            transform: translateX(100%);
            transition: transform 0.4s cubic-bezier(0.22, 1, 0.36, 1);

            z-index: 1000;
            display: flex;
        }

        #sideSheet.active {
            transform: translateX(0);
        }

        /* Content layout */
        #sideSheet .sheet-content {
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        /* Header */
        #sideSheet .sheet-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 18px 20px;
            border-bottom: 1px solid #eee;
            background: #fafafa;
        }

        #sideSheet .title {
            font-size: 18px;
            font-weight: 600;
        }

        /* Close button */
        #sideSheet .close-btn {
            border: none;
            background: #f1f1f1;
            font-size: 16px;
            padding: 6px 10px;
            cursor: pointer;
            border-radius: 6px;
            transition: 0.2s;
        }

        #sideSheet .close-btn:hover {
            background: #e0e0e0;
        }

        /* Body */
        #sideSheet .sheet-body {
            padding: 20px;
            flex: 1;
            overflow-y: auto;
            animation: fadeIn 0.4s ease;
        }

        /* Footer */
        .sheet-footer {
            padding: 15px 20px;
            border-top: 1px solid #eee;
            background: #fff;
        }

        .sheet-footer button {
            width: 100%;
            padding: 12px;
            border: none;
            background: black;
            color: white;
            font-size: 16px;
            border-radius: 8px;
            cursor: pointer;
        }

        /* Spinner */
        .spinner {
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255,255,255,0.6);
            border-top: 2px solid white;
            border-radius: 50%;
            animation: spin 0.7s linear infinite;
            display: inline-block;
            vertical-align: middle;
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes spin {
            100% { transform: rotate(360deg); }
        }

        /* ================= RESPONSIVE ================= */

        /* Tablets */
        @media (max-width: 992px) {
            #sideSheet {
                width: 360px;
            }
        }

        /* Small tablets / large phones */
        @media (max-width: 768px) {
            #sideSheet {
                width: 100%;
            }

            #sideSheet .sheet-header {
                padding: 14px 16px;
            }

            #sideSheet .sheet-body {
                padding: 16px;
            }

            .sheet-footer {
                padding: 12px 16px;
            }
        }

        /* Mobile */
        @media (max-width: 480px) {
            #sideSheet {
                width: 100%;
            }

            #sideSheet .title {
                font-size: 15px;
            }

            #sideSheet .close-btn {
                font-size: 14px;
                padding: 5px 8px;
            }

            #sideSheet .sheet-body {
                padding: 14px;
            }

            .sheet-footer button {
                font-size: 15px;
                padding: 11px;
            }
        }

        /* Extra small devices */
        @media (max-width: 360px) {
            #sideSheet .sheet-header {
                padding: 12px;
            }

            #sideSheet .sheet-body {
                padding: 12px;
            }

            .sheet-footer {
                padding: 10px 12px;
            }

            .sheet-footer button {
                font-size: 14px;
                padding: 10px;
            }
        }
        .spinner {
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255,255,255,0.6);
            border-top: 2px solid white;
            border-radius: 50%;
            animation: spin 0.7s linear infinite;
            display: inline-block;
            vertical-align: middle;
        }

        @keyframes spin {
            100% { transform: rotate(360deg); }
        }
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
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
                                   value="<?= $load['fmsname']!==null?$load['fmsname']:''?>" required>

                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="username" class="col-md-6 control-label">Details<span class="red_small">*</span></label>
                        <div class="col-md-6">
                            <input name="fms_details" type="text" class="form-control" disabled
                                   value="<?= $load['details']!==null?$load['details']:''?>" required>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-center mt-5">
            </div>

            <div class="mt-5 text-right" style="margin-top: 100px;margin-bottom: 10px">

            </div>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <table width="95%" id="myacc-users-grid" class="display table-striped table table-hover" style="margin-top:4px;" align="center" cellpadding="4" cellspacing="0" border="1">
                            <thead class="bg-primary">
                            <tr>
                                <td>#</td>
                                <td>Form Name</td>
                                <td>Display Name</td>
                                <td>Status</td>
                                <td>View</td>
                                <td>Upload</td>
                                <td>API</td>
                                <td>Publish</td>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            <div class="text-center">
                <span class="btn btn-primary" onclick="window.location.href='fms_view.php?pid=<?=$_REQUEST['pid']?>&hid=<?=$_REQUEST['hid']?>'" style="text-transform: capitalize"><span id="operation_name">back</span></span>
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
                <div style="display: flex;justify-content: space-between">
                    <button class="btn" onclick="copyText('getReq')">Copy</button>
                    <a id="download_file" class="btn btn-primary" href="download_ap.php?pid=<?=$_REQUEST['pid']?>&hid=<?=$_REQUEST['hid']?>&id=<?=$_REQUEST['pid']?>">Download</a>
                </div>
            </div>
            <div class="code">
                <span>curl --location https://fms.cancrm.in/fmsapi/fms-fetch.php</span>
                <span>--header   ' Authorization: Basic dGVzdDoxMjM= '</span>
            </div>
            <div class="code" id="getReq">
                <span>curl --location '<span id="curl"></span>'</span>
                <span>--header   ' Fms-Id: <?=$load['id']?> '</span>
                <span>--header   ' Fms-Name: <?=$load['fmsname']?> '</span>
                <span>--header   ' Form-Id: <span id="form_id">43</span> '</span>
                <span>--header   ' Form-Name: <span id="form_name">feedback form</span> '</span>
                <span>--header   ' Content-Type: application/json '</span>
                <span>--header   ' Authorization: Basic dGVzdDoxMjM= '</span>
                <span>--data-raw  '{</span>
                <span id="data_raw"></span>
                <span>}'</span>
            </div>
        </div>
    </div>
    <button id="ok" class="ok btn btn-primary" onclick="closeSheet()">OK</button>
</section>

<section id="sideSheet">
    <div class="sheet-content">

        <div class="sheet-header">
            <h2 class="title">Publish Form</h2>
            <button class="close-btn" onclick="closeSheet_1()">✕</button>
        </div>

        <div class="sheet-body">
            <h3 style="text-align: center">QR Code = <span id="qr_name"></span></h3>
            <div id="qrcode"></div>
        </div>
        <div class="sheet-footer">
            <div style="display: flex;justify-content: space-between;margin-bottom: 5px;">
                <button style="margin-right: 5px;" onclick="downloadQR()">Download QR</button>
                <button id="publishBtn" onclick="handlePublish()"
                        style="margin-left:5px; background-color:#00CC00; color:white; padding:8px 16px; border:none; cursor:pointer; display:flex; align-items:center; gap:8px;">
                    <span id="btnText">Publish</span>
                    <span id="loader" style="display:none;" class="spinner"></span>
                </button>
            </div>
            <button style="background-color: darkred;" onclick="closeSheet_1()">Cancel</button>
        </div>
    </div>
</section>

<?php
include("../includes/footer.php");
include("../includes/connection_close.php");
?>
<script>
    document.getElementById('curl').textContent=`https://${window.location.host}/fmsapi/fmsconnect.php`;
    console.log(window.location.host);
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
        let i=0;
        column.forEach(element => {
            let space="    ";
            data_raw_element.innerHTML +=`${i===0?'':space.repeat(4)} <span style="margin-bottom: 15px;margin-top: 15px;">"${element}" : " " ,</span><br/>`;
            i++;
        })
        // data_raw_element.innerHTML = data_row;?
        openSheet();
        DownloadFile('<?=$_REQUEST['pid']?>','<?=$_REQUEST['hid']?>','<?=$_REQUEST['id']?>',formId,formName,el.dataset.column);
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
    function DownloadFile(pid,hid,id,formid,formName,col) {
        document.getElementById("download_file").href = `download_ap.php?pid=${pid}&hid=${hid}&id=${id}&formid=${formid}&formname=${formName}&col=${btoa(col)}`;
    }
</script>
<script>
    function showQRCode(element){
        const btn = document.getElementById("publishBtn");
        console.log(element)
        const formid=element.dataset.fromid;
        const formname=element.dataset.formname;
        btn.dataset.formid=formid;
        document.getElementById("qr_name").innerHTML=formname;
        document.getElementById("sideSheet").classList.add("active");
        createQRCode(`https://fms.cancrm.in/admin/publishqrcode.php?formid=${formid}&formname=${formname}`);
    }

    function closeSheet_1(){
        document.getElementById("sideSheet").classList.remove("active");
    }


    function createQRCode(data){
        const container = document.getElementById("qrcode");

        container.innerHTML = ""; // clear old QR

        new QRCode(container, {
            text: data,
            width: 200,
            height: 200
        });

        document.getElementById("sideSheet").classList.add("active");
    }
    function downloadQR(){
        const qrContainer = document.getElementById("qrcode");

        // case 1: canvas (qrcodejs default)
        const canvas = qrContainer.querySelector("canvas");

        if(canvas){
            const link = document.createElement("a");
            link.href = canvas.toDataURL("image/png");
            link.download = "qr-code-form.png";
            link.click();
            return;
        }

        // case 2: img (agar PHP ya API use kar rahe ho)
        const img = qrContainer.querySelector("img");

        if(img){
            const link = document.createElement("a");
            link.href = img.src;
            link.download = "qr-code.png";
            link.click();
            return;
        }

        alert("QR code not found!");
    }

    function handlePublish() {
        const btn = document.getElementById("publishBtn");
        const text = document.getElementById("btnText");
        const loader = document.getElementById("loader");

        btn.disabled = true;
        text.innerText = "Publishing...";
        loader.style.display = "inline-block";

        const formid=btn.dataset.formid;

        // API call yahan lagao
        setTimeout(() => {
            text.innerText = "Published";
            loader.style.display = "none";
            btn.disabled = false;

            // Close sheet after 1 sec
            setTimeout(closeSheet_1, 1000);

            // Redirect after total ~1.5 sec
            setTimeout(() => {
                window.location.href = `../publish/publishqrcode.php?pid=1&hid=Masters&formid=${formid}&uuid=&token=${generateToken(20)}`;
            }, 1500);

        }, 2000);
    }

    function generateToken(length = 6) {
        return Math.floor(Math.random() * Math.pow(10, length))
            .toString()
            .padStart(length, '0');
    }
</script>
</body>
</html>