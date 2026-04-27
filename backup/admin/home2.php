<?php
require_once("../includes/config.php");
$tabstr = '';
$tab_for = '';

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
<link rel="stylesheet" href="../css/home_new.css">
 <script type="text/javascript" src="../js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="../css/dataTables.responsive.css">
    <script type="text/javascript" src="../js/dataTables.responsive.min.js"></script>
 <script type="text/javascript" language="javascript" >
$(document).ready(function() {
	var dataTable = $('#admin-grid').DataTable( {
		"responsive": true,
        "processing": true,
        "serverSide": true,
		"ajax":{
			url :"../pagination/adminusr-grid-data.php", // json datasource
			data: { "pid": "<?=$_REQUEST['pid']?>", "hid": "<?=$_REQUEST['hid']?>", "status": "<?=$_REQUEST['status']?>"},
			type: "post",  // method  , by default get
			error: function(){  // error handling
				$(".admin-grid-error").html("");
				$("#admin-grid").append('<tbody class="admin-grid-error"><tr><th colspan="8">No data found in the server</th></tr></tbody>');
				$("#admin-grid_processing").css("display","none");
				
			}
		}
	} );
} );
</script>
<title><?=siteTitle?></title>
    <style>
        /* Card Base */
        .card {
            border: none;
            border-radius: 12px;
            background: #ffffff;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        /* Shadow + hover effect */
        .card {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        /* Title */
        .card h6 {
            font-size: 14px;
            color: #6c757d;
            margin-bottom: 5px;
        }

        /* Numbers */
        .card h3 {
            font-size: 28px;
            font-weight: 700;
            color: #212529;
        }

        /* Button */
        .card .btn {
            border-radius: 20px;
            padding: 4px 12px;
            font-size: 12px;
        }

        /* Optional: colored cards */
        .card.primary {
            border-left: 5px solid #0d6efd;
        }

        .card.success {
            border-left: 5px solid #198754;
        }

        .card.warning {
            border-left: 5px solid #ffc107;
        }
    </style>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/highcharts-more.js"></script>
</head>
<body>
<div class="container-fluid">
    <div class="row content">
        <?php
        include("../includes/leftnav2.php");
        ?>
        <div class="<?=$screenwidth?> tab-pane fade in active" id="home">
            <h2 align="center" class="ntxt" style="border-bottom:1px solid #aaa8a8;padding:25px 0px;margin:0px;"><i class="fa fa-users"></i> Welcome Page </h2>
            <?php if($_REQUEST['msg']){?><br>
                <h4 align="center" style="color:#FF0000"><?=$_REQUEST['msg']?></h4>
            <?php }?>

            <?php
            if(PermissionManager::checkViewRights($link1,$_SESSION['userid'],'1')){
                ?>
                <div class="container-fluid mt-4">
                    <div class="row g-3" style="margin-top: 10px">

                  <!-- Total FMS -->
                  <div class="col-md-4">
                      <div class="card shadow-sm" style="padding: 10px;text-align: center;border: 0.5px solid rgba(128,128,128,0.46)">
                          <div class="d-flex justify-content-between align-items-center">
                              <a href="fms_master.php" style="text-decoration: none">
                                  <div>
                                      <h6 class="text-muted">Total FMS</h6>
                                      <h3 class="fw-bold">
                                          <?php
                                          $sql="SELECT COUNT(*) as total FROM `fms_master`";
                                          $result=mysqli_query($link1, $sql);
                                          $value='';
                                          while ($row=mysqli_fetch_assoc($result)) {
                                              echo $row['total'];
                                          }
                                          ?>
                                      </h3>
                                  </div>
                              </a>
                          </div>
                      </div>
                  </div>

                  <div class="col-md-4">
                          <div class="card shadow-sm" style="padding: 10px;text-align: center;border: 0.5px solid rgba(128,128,128,0.46)">
                              <div class="d-flex justify-content-between align-items-center">
                                  <a href="adminusermgt.php" style="text-decoration: none;cursor: pointer">
                                      <div>
                                          <h6 class="text-muted">Total Users</h6>
                                          <h3 class="fw-bold">
                                              <?php
                                              $sql="SELECT COUNT(*) as total FROM `admin_users`";
                                              $result=mysqli_query($link1, $sql);
                                              $value='';
                                              while ($row=mysqli_fetch_assoc($result)) {
                                                  echo $row['total'];
                                              }
                                              ?>
                                          </h3>
                                      </div>
                                  </a>
                              </div>
                          </div>
                  </div>

                  <div class="col-md-4">
                      <div class="card shadow-sm" style="padding: 10px;text-align: center;border: 0.5px solid rgba(128,128,128,0.46)">
                          <div class="d-flex justify-content-between align-items-center">
                              <a href="role_master.php" style="text-decoration: none">
                                  <div>
                                      <h6 class="text-muted">Total Roles</h6>
                                      <h3 class="fw-bold">
                                          <?php
                                          $sql="SELECT COUNT(*) as total FROM `usertype_master`";
                                          $result=mysqli_query($link1, $sql);
                                          $value='';
                                          while ($row=mysqli_fetch_assoc($result)) {
                                              echo $row['total'];
                                          }
                                          ?>
                                      </h3>
                                  </div>
                              </a>
                          </div>
                      </div>
                  </div>
              </div>
                    <div class="row g-3 card " style="margin-top: 10px;margin-left: 1px;padding: 10px;">
				  <div style="text-align:center">
				  <h4 style="text-transform: capitalize;font-weight: normal!important;">last Activity : <?= $_SESSION['userid'] ?> </h4>
				  </div>
                  <table width="95%" class="table table-striped table-hover table-bordered table-responsive">
                      <thead class="bg-primary">
                      <tr>
                          <td>Sr.</td>
                          <td>UserId</td>
                          <td>RefNo</td>
                          <td>Activity</td>
                          <td>Action</td>
                          <td>Date</td>
                      </tr>
                      </thead>
                      <tbody>
                      <?php
                      $userid=$_SESSION['userid'];
                      $sql = "SELECT * FROM daily_activities WHERE userid='$userid' ORDER BY id DESC LIMIT 10";
                      $result = mysqli_query($link1, $sql);

                      if (!$result) {
                          echo "<tr><td colspan='5'>Query failed</td></tr>";
                      } else {
                          $i = 1;
                          while ($row = mysqli_fetch_assoc($result)) {
                              echo "<tr>
                <td>".$i."</td>
                <td>".$row['userid']."</td>
                <td>".$row['ref_no']."</td>
                <td>".$row['activity_type']."</td>
                <td>".$row['action_taken']."</td>
                <td>".$row['update_date']."</td>
              </tr>";
                              $i++;
                          }
                      }
                      ?>
                      </tbody>
                  </table>
              </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div id="pie_chart"></div>
<!--                      ["PIE","BAR","LINE","AREA","SCATTER","COLUMN"] -->
                      <?=renderChart($_REQUEST['chart']??'LINE',
                              'this is Pie chart',
                              'Pie Chart subtitle','left','pie_chart')?>
                  </div>
                  <div class="col-md-6">
                      <div id="container_1"></div>
                  </div>
              </div>
              <div class="row g-3">
                  <div class="col-md-6">
                      <div id="container_2"></div>

                  </div>
                  <div class="col-md-6">
                      <div id="container_3"></div>

                  </div>
              </div>
          </div>

          <?php } ?>

	  </div>


  </div>
</div>
<?php
include("../includes/footer.php");
include("../includes/connection_close.php");
?>
<script>
    /*
     * Zero COnditional statement = universal fact ke liye use hota h , jo hamesh sach ho
     *   = Present Simple condition -> result present simple me banate hai
     *
     * First Condition statement = future me possible situations
     *   = if + Present Simple Condition -> result will+verb 1st form
     *
     * Second Conditional Statement ->  Present ya Future me unreal ( imageinary ) situations
     *  if + Past Simple Conditions -> would+base verb ke 1st form
     *
     * third conditional statement -> Past me kuch aur hota to result different hota.
     *  if +had + v3 , would have + v3
     *  If I had studied, I would have passed.
     */
    function Observer(){}
    Observer.prototype.notify=function(){
        throw new Error('Method not implemented.');
    }
    Observer.prototype.attach=function(){
        throw new Error('Method not implemented.');
    }
    Observer.prototype.detech=function(){
        throw new Error('Method not implemented.');
    }
    function Subscriber(){}
    Subscriber.prototype.update=function(update){
        throw new Error('Method not implemented.');
    }
    function ChartObserver(){
        Observer.call(this);
        this.data=null;
        this._observers=[];
    }
    ChartObserver.prototype=Object.create(Observer.prototype);
    ChartObserver.prototype.constructor=ChartObserver;
    ChartObserver.prototype.attach=function(subscriber){
        this._observers.push(subscriber);
    }
    ChartObserver.prototype.notify=function(){
        this._observers.forEach(subscriber=>{
            subscriber.update(this.data);
        });
    }
    ChartObserver.prototype.setData=function(data){
        this.data=data;
    }
    function BarSubscriber(){
        Subscriber.call(this);
    }
    BarSubscriber.prototype=Object.create(Subscriber.prototype);
    BarSubscriber.prototype.constructor=BarSubscriber;
    BarSubscriber.prototype.update=function(data){
        console.log(data);
    }

    function LineSubscrier(){
        Subscriber.call(this);
    }
    LineSubscrier.prototype=Object.create(Subscriber.prototype);
    LineSubscrier.prototype.constructor=LineSubscrier;
    LineSubscrier.prototype.update=function(data){
        console.log(data);
    }


    function LNode(data){
        this.data=data;
        this.next=null;
    }
    function LinkedList(){
        this.head=null;
    }

    LinkedList.prototype.addNode=function(data){
        const node=new LNode(data);
        if(this.head===null){
            this.head=node;
            return node;
        }
        let temp=this.head;
        while (temp.next!==null){
            temp=temp.next;
        }
        temp.next=node;
        return node;
    }

    const observer=new ChartObserver();
    observer.attach(new BarSubscriber());
    observer.attach(new LineSubscrier());
    const data_=new LinkedList();
    data_.addNode(12);
    data_.addNode(13);
    data_.addNode(14);
    data_.addNode(15);
    observer.setData(data_);
    observer.notify();
</script>
</body>
</html>