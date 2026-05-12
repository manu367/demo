<?php
require_once("../includes/config.php");

require '../vendor/autoload.php';

use Smalot\PdfParser\Parser;
function createUploadDir($dir){
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
}

function imageUpload($imageFile){
    $allowedTypes = [
            'image/jpeg',
            'image/png',
            'image/jpg',
            'image/webp'
    ];

    if (!in_array($imageFile['type'], $allowedTypes)) {
        return [
                'status' => false,
                'message' => 'Only image files are allowed.'
        ];
    }

    if ($imageFile['error'] !== 0) {
        return [
                'status' => false,
                'message' => 'Image upload error.'
        ];
    }

    $uploadDir = "../uploads_ocr/images/";
    createUploadDir($uploadDir);

    $fileName = time() . "_" . basename($imageFile['name']);
    $filePath = $uploadDir . $fileName;

    if (move_uploaded_file($imageFile['tmp_name'], $filePath)) {
        $imageInfo = getimagesize($filePath);
        $ocrText = "";
        try {
            $tesseractPath = "C:/Program Files/Tesseract-OCR/tesseract.exe";

            $command = '"' . $tesseractPath . '" "' . $filePath . '" stdout 2>&1';

            $ocrText = shell_exec($command);

        }
        catch (Exception $e) {
            $ocrText = "OCR Failed: " . $e->getMessage();
        }
        return [
                'status' => true,
                'message' => 'Image uploaded successfully.',
                'file_path' => $filePath,
                'image_data' => [
                        'width' => $imageInfo[0],
                        'height' => $imageInfo[1],
                        'mime' => $imageInfo['mime']
                ],
                'extracted_text' => $ocrText
        ];
    }

    return [
            'status' => false,
            'message' => 'Failed to upload image.'
    ];
}


function pdfUpload($pdfFile)
{
    $allowedTypes = ['application/pdf'];

    if (!in_array($pdfFile['type'], $allowedTypes)) {
        return [
                'status' => false,
                'message' => 'Only PDF files are allowed.'
        ];
    }

    if ($pdfFile['error'] !== 0) {
        return [
                'status' => false,
                'message' => 'PDF upload error.'
        ];
    }

    $uploadDir = "../uploads_ocr/pdfs/";
    createUploadDir($uploadDir);

    $fileName = time() . "_" . basename($pdfFile['name']);
    $filePath = $uploadDir . $fileName;

    if (move_uploaded_file($pdfFile['tmp_name'], $filePath)) {

        $parser = new Parser();
        $pdf = $parser->parseFile($filePath);
        $text = $pdf->getText();

        return [
                'status' => true,
                'message' => 'PDF uploaded successfully.',
                'file_path' => $filePath,
                'pdf_text' => $text
        ];
    }

    return [
            'status' => false,
            'message' => 'Failed to upload PDF.'
    ];
}

if (isset($_FILES['form_upload_file'])) {
    $file = $_FILES['form_upload_file'];

    if (strpos($file['type'], 'image/') !== false) {
        $response = imageUpload($file);
        if ($response['status']) {
//            echo "<h3>" . $response['message'] . "</h3>";
//            echo "<b>Image Info:</b>";
//            echo "<pre>";
//            print_r($response['image_data']);
//            echo "</pre>";
            echo "<b>Extracted Text:</b>";
            echo "<pre>";
            echo htmlspecialchars($response['extracted_text']);
            echo "</pre>";
        } else {
            echo $response['message'];
        }
    }

    elseif ($file['type'] == 'application/pdf') {
        $response = pdfUpload($file);
        if ($response['status']) {
            echo "<h3>" . $response['message'] . "</h3>";
            echo "<b>PDF Text:</b>";
            echo "<pre>";
            echo htmlspecialchars($response['pdf_text']);
            echo "</pre>";
        } else {
            echo $response['message'];
        }
    }


    else {
        echo "Unsupported file type.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?=siteTitle?></title>
    <script src="../js/jquery.min.js"></script>
    <link href="../css/font-awesome.min.css" rel="stylesheet">
    <link href="../css/abc.css" rel="stylesheet">
    <script src="../js/bootstrap.min.js"></script>
    <link href="../css/abc2.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/bootstrap-select.min.css">
    <script src="../js/bootstrap-select.min.js"></script>
    <link rel="stylesheet" href="../css/jquery.dataTables.min.css">
    <script type="text/javascript" src="../js/jquery.dataTables.min.js"></script>
    <script src="../js/frmvalidate.js"></script>
    <script type="text/javascript" src="../js/jquery.validate.js"></script>
    <script type="text/javascript" src="../js/common_js.js"></script>
    <link rel="stylesheet" href="../css/datepicker.css">
    <script src="../js/bootstrap-datepicker.js"></script>
    <script src="../js/fileupload.js"></script>
    <style>
        .toast {
            position: fixed;
            top: 20px;
            right: -350px;
            display: flex;
            align-items: center;
            gap: 10px;
            backdrop-filter: blur(8px);
            color: #fff;
            padding: 14px 18px;
            border-radius: 10px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
            font-size: 14px;
            font-weight: bold;
            min-width: 250px;
            max-width: 300px;
            z-index: 1000;
            transition: all 0.4s ease;
            opacity: 0;
        }

        .toast.show {
            right: 20px;
            opacity: 1;
        }

        .toast .icon {
            font-size: 18px;
        }

        .toast .message {
            flex: 1;
        }
        .toast::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            height: 3px;
            width: 100%;
            background: #fff;
            animation: progress 60s linear;
        }

        @keyframes progress {
            from { width: 100%; }
            to { width: 0%; }
        }
    </style>
    <script>
        window.addEventListener("load", function() {
            const toast = document.getElementById("errorPopup");
            if (toast) {
                setTimeout(() => {
                    toast.classList.add("show");
                }, 300); // small delay for smooth entry

                setTimeout(() => {
                    toast.classList.remove("show");
                }, 60000); // hide after 3s
            }
        });
    </script>
</head>
<body>

<?php
if(isset($_REQUEST['msg'])){?>
    <div id="errorPopup" class="toast" style="<?=isset($_REQUEST['type'])?'background: green;':'background: #cd1a1a;'?>">
        <span class="message"><?=htmlspecialchars($_GET['msg'], ENT_QUOTES, 'UTF-8');?></span>
    </div>
<?php } ?>

<div class="container-fluid">
    <div class="row content">
        <?php
        include("../includes/leftnav2.php");
        ?>
        <div class="<?=$screenwidth?> tab-pane fade in active" id="home">
            <h2 align="center"><i class="fa fa-upload"></i> Optical Character Recognition</h2>

            <div class="form-group"  id="page-wrap" style="margin-left:10px;margin-top: 50px;">
                <form  name="frm1"  id="frm1" class="form-horizontal" action="" method="post"  enctype="multipart/form-data">
                    <div class="form-group">
                        <div class="col-md-12"><label class="col-md-4 control-label">Upload File</label>
                            <div class="col-md-4">
                                <input type="file" name="form_upload_file" class="form-control" accept="image/*,application/pdf">
                            </div>
                        </div>
                    </div>

                    <div class="form-group" style="margin-top: 20px">
                        <div class="col-md-12">
                            <div class="col text-center">
                                <input type="submit" name="upload_file" class="btn btn-success" value="upload">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php
include("../includes/footer.php");
include("../includes/connection_close.php");
?>
</body>
</html>