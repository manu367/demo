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

$report=new Reportuploader($link1);
$flag=false;
if(isset($_POST['upload_file'])){

    $tableName=$_POST['table_name'];
    $fms_id=$_POST['fms_id'];
    $form_id=$_POST['form_id'];

    mysqli_autocommit($link1, false);
    $redirect="formid=".$_POST['form_id']."&msg=";



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
        if($report->validateSheetColumn($tableName,$sheet_column)){
            $highestRow = $sheet->getHighestDataRow();
            $data=getAllExcelData($sheet,$highestRow);
            if($data){
                try{
                    $flag=$report->insertData($data,$tableName,$_SESSION['userid'],$_SERVER['REMOTE_ADDR']);
//                    $flag=true;
                }catch (Exception $e){
                    throw new GlobalException($redirect.$e->getMessage());
                }
            }else{
                throw new GlobalException($redirect.'Empty Data is not Uploaded');
            }
        }else{
            throw new GlobalException($redirect."Excel Sheet Column are not match with sheet");
        }

    }

    if($flag){
        header('location:form_upload.php?formid='.$form_id.'&type=scuccess&msg=Successfully uploaded data');
        exit();
    }

}