<?php
require_once("../includes/config.php");
require_once ("../ExcelExportAPI/Classes/PHPExcel.php");
require_once ("../ExcelExportAPI/Classes/PHPExcel/IOFactory.php");
global $link1;

set_exception_handler(function ($exception) {
   if($exception instanceof GlobalException){
       header('location:form_upload.php?'.$exception->getMessage());
       exit();
   }
});

function urlExists($url) {
    $headers = get_headers($url);
    return strpos($headers[0], '200') !== false;
}
function getMimeType($url) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_NOBODY, true); // only headers
    curl_exec($ch);
    $mime = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
    curl_close($ch);
    return $mime;
}

$report=new Reportuploader($link1);
$flag=false;
if(isset($_POST['upload_file'])){
    //var_dump(urlExists('https://img.freepik.com/free-vector/flat-contact-us-background_23-2148181316.jpg'));
    //var_dump(getMimeType('https://img.freepik.com/free-vector/flat-contact-us-background_23-2148181316.jpg'));
    //var_dump(getMimeType('https://mjpru.ac.in/pdf/bca.pdf'));
    $tableName=$_POST['table_name']??'';
    $fms_id=$_POST['fms_id']??'';
    $form_id=$_POST['form_id']??'';

    mysqli_autocommit($link1, false);
    $redirect="pid=".$_REQUEST['pid']."&hid=".$_REQUEST['hid']."&formid=".$_POST['form_id']."&msg=";

    if($tableName==="" && $fms_id==="" && $form_id==""){throw new GlobalException($redirect."value not be empty");}
    $filename=null;

    // Task 1= file upload kar rha hu yha  = done
    try{
        $filename=fileUploader($_FILES);

    }catch (Exception $e){
        mysqli_rollback($link1);
        throw new GlobalException($redirect.$e->getMessage());
    }

    if($filename){
        $sheet=loadSheets($filename);
        $sheet_column=sheetcolumn($sheet);
        // yha me check karunga ke column= file type h to file = https://
//        $form_units_data=getAllFormUnits($link1,$form_id);
//        var_dump($form_units_data);exit();
        // problem = how to vaidate correct image or not and e

        try{
            $valid=$report->validateSheetColumn($form_id,$tableName,$sheet_column);
        }catch (Exception $e){
            throw new GlobalException($redirect.$e->getMessage());
        }

        if($valid){
            $highestRow = $sheet->getHighestDataRow();

            $data=getAllExcelData($sheet,$highestRow);
            if($data){
                try{
                    $flag=$report->insertData($data,$tableName,$_SESSION['userid'],$_SERVER['REMOTE_ADDR'],$form_id);
//                    $flag=true;
                }catch (Exception $e){
                    throw new GlobalException($redirect.$e->getMessage());
                }
            }else{
                throw new GlobalException($redirect.'Empty Data is not Uploaded');
            }
        }
        else{
            throw new GlobalException($redirect."Excel Sheet Column are not match with sheet");
        }

    }
    else{
        throw new GlobalException("File not Uploaded");
    }

    if($flag){
        operationtracker($link1,$_SESSION['userid'],'form_upload_data',"Upload form Data",'ADD',$_SERVER['REMOTE_ADDR']);
        header('location:form_upload.php?formid='.$form_id.'&type=scuccess&msg=Successfully uploaded data');
        exit();
    }
}