<?php
require_once("../includes/config.php");
global $link1;
function getAllFMS_form($link1, $selectedValue = ''){
    $sql = "SELECT * FROM `form_master` ORDER BY `form_master`.`form_name` ASC";
    $result = mysqli_query($link1, $sql);

    if(!$result){
        return "<select class='form-control'><option>-- Error Fetching Data --</option></select>";
    }

    if(mysqli_num_rows($result) == 0){
        return "<select class='form-control'><option>-- No Data --</option></select>";
    }

    $selectBox = "<select class='form-control' name='form_name' onchange='this.form.submit()'>";
    $selectBox .= "<option value=''>-- Select FMS --</option>";

    while($row = mysqli_fetch_assoc($result)){
        $id = $row['id'];
        $name = $row['form_name'];

        $selected = ((string)$selectedValue === (string)$id) ? "selected" : "";

        $selectBox .= "<option value='$id' $selected>$name</option>";
    }

    $selectBox .= "</select>";

    return $selectBox;
}


$date_wise_data=date('Y-m-d', strtotime('-10 years')).' - '.date('Y-m-d');
if($_POST){
    $date_wise_data=$_POST['daterange'];
}
$data_fms_dom=getFormMaster_data($link1,$date_wise_data);
//var_dump($data_fms_dom[0]);
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
    <script type="text/javascript" src="../js/moment.js"></script>
    <link href="../css/abc2.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/bootstrap.min.css">

    <script src="../js/frmvalidate.js"></script>
    <script type="text/javascript" src="../js/jquery.validate.js"></script>
    <script type="text/javascript" src="../js/common_js.js"></script>
    <script type="text/javascript" src="../js/daterangepicker.js"></script>
    <link rel="stylesheet" type="text/css" href="../css/daterangepicker.css"/>
    <link rel="stylesheet" href="../css/datepicker.css">
    <!-- Include multiselect -->
    <title><?=siteTitle?></title>
    <link rel="stylesheet" href="../css/jquery.dataTables.min.css">
    <script type="text/javascript" src="../js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" language="javascript" >
        $(document).ready(function(){
            $('input[name="daterange"]').daterangepicker({
                locale: {
                    format: 'YYYY-MM-DD'
                }
            });
        });
    </script>
    <style>
        .modal {
            display: block;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }

        .modal-content {
            background: #fff;
            padding: 20px;
            margin: 15% auto;
            width: 300px;
            text-align: center;
            border-radius: 10px;
        }

        button {
            padding: 8px 15px;
            cursor: pointer;
        }
    </style>
    <link rel="stylesheet" href="../css/dataTables.responsive.css">
    <script type="text/javascript" src="../js/dataTables.responsive.min.js"></script>
    <script src="../js/highcharts.js"></script>
    <script src="../js/highcharts-more.js"></script>
</head>
<body>
<div class="container-fluid">
    <div class="row content">
        <?php
        include("../includes/leftnav2.php");
        ?>
        <div class="<?=$screenwidth?> tab-pane fade in active" id="home">
            <h2 align="center"><i class="fa fa-pencil-square-o"></i> Form Analysis</h2>
            <div class="container-fluid" style="margin-top: 50px">
                <div class="row">
                    <div class="col">
                        <form class="form-horizontal" role="form" name="form1"  id="form1" action="" method="post">

                            <div class="form-group">
                                <div id= "dt_range" class="col-md-6">
                                    <label class="col-md-5 control-label">Date Range</label>
                                    <div class="col-md-6 input-append date" align="left">
                                        <input type="text" name="daterange" id="date_rng" class="form-control" value="<?=$_REQUEST['daterange']?>" />
                                    </div>
                                </div>
                                <div class="col-md-6"><label class="col-md-5 control-label">Form Name</label>
                                    <div class="col-md-5" align="left">
                                       <?php
                                       $selectedFms = $_REQUEST['form_name'] ?? '';
                                       echo getAllFMS_form($link1, $selectedFms);
                                       ?>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="submit" name="go" class="btn btn-success">Go</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div id="fms_other_chart"></div>
                        <!-- ["PIE","BAR","LINE","AREA","SCATTER","COLUMN"] -->
                        <?=renderChart('COLUMN',
                            'Form Input Data',
                            'Paramter/type/Length','left','fms_other_chart')?>
                    </div>
                    <div class="col-md-6">
                        <div id="container_1">
                               <div id="active_inactive_chart"></div>
                            <?=renderChart('PIE',
                                    'Form Active',
                                    'ACTIVE form CHART','left','active_inactive_chart')?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<?php
include("../includes/footer.php");
include("../includes/connection_close.php");
?>
<script>
    function FormData(){
        this.data=null;
    }
    /*



Para/Sentence Completion
Column Based
Sentence Rearrangement
Word Swap
Sentence Based Error
Spelling Errors
Connectors

[
  {
    "correct_answer": "c",
    "options": {
      "a": "25",
      "b": "40",
      "c": "50",
      "d": "75"
    },
    "question": "What is 25% of 200?"
  }
]
     */
</script>
</body>
</html>