<?php

class FmsOperations{
    public static function getAllFMS($link1,$userId){
        $fmsMode=[];

        $fmsRepo=new FmsRepo($link1);
        $allFms=$fmsRepo->getAllFMS($userId);

        for($i = 0; $i < count($allFms); $i++){
            $id = $allFms[$i]['fms_id'];
            $fmsname = $allFms[$i]['fmsname'];
            $details = $allFms[$i]['details'];
            $steps = $allFms[$i]['steps'];
            $total_form = $allFms[$i]['total_form'];
            $status = $allFms[$i]['status'];
            $table_name = $allFms[$i]['table_name'];
            $fms_model = new FMSDetailsModel(
                $id, $fmsname, $details, $steps, $total_form, $status, $table_name
            );

            $data= self::getAllformMaster($link1, $id);
            $fms_model->setFormMaster($data);
            $fmsMode[]=$fms_model;
        }

        return $fmsMode;
    }

    private static function getAllformMaster($link1,$fmsid){
        $form_data=[];
        $data=FormRepository::getAllFormsByFMsid($link1,$fmsid);
        for($i=0; $i < count($data); $i++){
            $id = $data[$i]['id'];
            $form_name = $data[$i]['form_name'];

            $parameter_name=json_decode($data[$i]['parameter_name']);
            $display_name=json_decode($data[$i]['display_name']);
            $type=json_decode($data[$i]['type']);
            $drop_down=json_decode($data[$i]['drop_down']);
            $length=json_decode($data[$i]['length']);
            $frm_seq=json_decode($data[$i]['frm_seq']);
            $param_require=json_decode($data[$i]['param_require']);

            $status=$data[$i]['status'];
            $basic_details=[];
            for($j=0; $j < count($parameter_name); $j++){
                $basic_details[]=new FormBasicDetails($parameter_name[$j],
                    $display_name[$j],
                    $length[$j]??50,
                    $param_require[$j]??'',
                    $type[$j]??0,
                    $drop_down[$j]??0);
            }
            $form_data[]=new FormMasterModal($id,$form_name,$status,$frm_seq,$basic_details);
        }
        return $form_data;
    }

}