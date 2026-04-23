<?php

class SaveChartsData{
    private $connection;
    private $updateip,$updatedBy;
    private $service;
    public function __construct($connection){
        $this->connection = $connection;
        $this->service=new ChartService($this->connection);

    }
    public function saveChartData($data){

        if (!isset($data['chart']) || trim($data['chart']) === '') {
            throw new Exception("Chart type is required");
        }

        if (!isset($data['fms_id']) || trim($data['fms_id']) === '') {
            throw new Exception("fms_id is required");
        }

        if (!isset($data['charttitle']) || trim($data['charttitle']) === '') {
            throw new Exception("Chart title is required");
        }

        if (!isset($data['parameter_value']) || trim($data['parameter_value']) === '') {
            throw new Exception("X-axis value is required");
        }

        if (!isset($data['parameter_value_2']) || trim($data['parameter_value_2']) === '') {
            throw new Exception("Y-axis value is required");
        }

        if (!isset($data['operations']) || trim($data['operations']) === '') {
            throw new Exception("Operation is required");
        }

        if ($data['parameter_value'] === $data['parameter_value_2']) {
            throw new Exception("Parameter values must not be the same");
        }

        // Optional fields (as per your instruction — no strict validation)
        $table_name  = $data['tablename']  ?? null;
        $status      = '1'     ?? null;
        $updated_by  = $_SESSION['userid']  ?? null;
        $updated_ip  = $_SERVER['REMOTE_ADDR']  ?? null;

        $cleanData = [
            'chart'              => trim($data['chart']),
            'charttitle'         => trim($data['charttitle']),
            'parameter_value'    => trim($data['parameter_value']),
            'parameter_value_2'  => trim($data['parameter_value_2']),
            'operations'         => trim($data['operations']),
            'table_name'         => $table_name,
            'status'             => $status,
            'updated_by'         => $updated_by,
            'updated_ip'         => $updated_ip,
        ];
        return ($this->service)->saveChartService($cleanData);
    }
}