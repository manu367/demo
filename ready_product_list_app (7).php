<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= siteTitle ?></title>
<link rel="shortcut icon" href="../images/titleimg.png" type="image/png">
<script src="../js/jquery.js"></script>
<link rel="stylesheet" href="../css/bootstrap.min.css">
<link rel="stylesheet" href="../css/font-awesome.min.css">
<script src="../js/bootstrap-cspl.min.js"></script>

<style>
body {
  background: #f8f9fa;
  font-family: 'Roboto', sans-serif;
  font-size: 14px;
}
h2 {
  text-align: center;
  font-weight: 600;
  color: #333;
  margin: 20px 0;
}
.container-fluid {
  padding: 10px;
}
.search-box {
  text-align: center;
  margin-bottom: 15px;
}
.search-box input {
  width: 90%;
  max-width: 400px;
  padding: 8px 12px;
  border: 1px solid #ccc;
  border-radius: 6px;
  font-size: 14px;
}
.card-item {
  background: #fff;
  border: ridge;
  border-radius: 8px;
  padding: 10px 15px;
  margin-bottom: 12px;
  box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}
.row-line {
  display: flex;
  flex-direction: row;
  align-items: flex-start;  /* align labels to top for multi-line wrapping */
  padding: 6px 0;
  border-bottom: 1px dashed #eee;
}

.row-line:last-child {
  border-bottom: none;
}

.label {
  width: 35%;
  min-width: 120px;
  font-weight: 600;
  color: #333;
  text-align: left;
  margin-right: 10px;
  white-space: nowrap;  /* prevent label from wrapping */
}

.value {
  flex: 1;                /* take remaining space */
  color: #007bff;
  text-align: left;
  word-break: break-word; /* wrap long words naturally */
  white-space: normal;    /* allow wrapping */
}

.btn-scan {
  background-color: #007bff;
  color: white;
  border: none;
  padding: 5px 10px;
  border-radius: 4px;
  font-size: 13px;
}
.btn-mrp {
  background-color: #28a745;
  color: white;
  border: none;
  padding: 5px 10px;
  border-radius: 4px;
  font-size: 13px;
  margin-right: 6px;
}
.pagination {
  text-align: center;
  margin-top: 15px;
}
.pagination a {
  display: inline-block;
  padding: 6px 10px;
  border: 1px solid #007bff;
  color: #007bff;
  margin: 2px;
  border-radius: 4px;
  text-decoration: none;
}
.pagination a.active, .pagination a:hover {
  background-color: #007bff;
  color: white;
}
.alert { margin-top: 10px; }

@media (max-width:600px){
  .row-line {
    flex-direction: row;
  }
  .label {
    width: 45%;
  }
  .value {
    width: 55%;
    padding-left: 6px;
  }
}
</style>

</head>

<body>
<div class="container-fluid">
  <div class="row content">
    <div class="col-12 col-md-8 offset-md-2">
      <h2 style="font-size:18px; margin-bottom:10px;">
  <i class="fa fa-list"></i> Ready Product List
</h2>

      <div class="search-box">
        <form method="get" action="">
          <input type="hidden" name="user_id" value="<?= htmlspecialchars($user_id) ?>">
          <input type="text" name="search" placeholder="Search" value="<?= htmlspecialchars($search) ?>" onkeypress="if(event.key==='Enter'){this.form.submit();}">
        </form>
      </div>

      <?php if (isset($_REQUEST['msg'])) { ?>
        <div class="alert alert-<?= $_REQUEST['chkflag'] ?> alert-dismissible" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <strong><?= $_REQUEST['chkmsg'] ?>!</strong>&nbsp;&nbsp;<?= $_REQUEST['msg'] ?>.
        </div>
      <?php } ?>

      <?php
      $i = $offset;
      if (mysqli_num_rows($query) > 0) {
        while ($row = mysqli_fetch_assoc($query)) {
          $i++;
          $status = getAnyDetails($row["job_no"], "status", "system_ref_no", "jobcard_master", $link1);
          $partname = getAnyDetails($row["outcome_part"], "part_name", "partcode", "partcode_master", $link1);
          $type = getAnyDetails($row["outcome_part"], "itemtypeid", "partcode", "partcode_master", $link1);

          if ($type == 5) {
              $scanLink = "app_update_child_scan_details.php?doc_id=" . base64_encode($row['job_no']) . "&user_id=" . urlencode($user_id);
          } else {
              $scanLink = "app_update_scan_details.php?doc_id=" . base64_encode($row['job_no']) . "&user_id=" . urlencode($user_id);
          }

          //  Add MRP Link
          $mrpLink = "update_mrp_details_app.php?doc_id=" . base64_encode($row['job_no']) . "&user_id=" . urlencode($user_id);
      ?>
      <div class="card-item">
        <div class="row-line"><div class="label">System Ref No.</div><div class="value"><?= htmlspecialchars($row["system_ref_no"]) ?></div></div>
        <div class="row-line"><div class="label">Location</div><div class="value"><?= htmlspecialchars($row["location_code"]) ?></div></div>
        <div class="row-line"><div class="label">Product</div><div class="value"><?= utf8_encode($partname) ?></div></div>
        <div class="row-line"><div class="label">Partcode</div><div class="value"><?= htmlspecialchars($row["outcome_part"]) ?></div></div>
        <div class="row-line"><div class="label">Qty</div><div class="value"><?= htmlspecialchars($row["outcome_qty"]) . " " . htmlspecialchars($row["purchase_unit"]) ?></div></div>
        <div class="row-line"><div class="label">Status</div><div class="value"><?= htmlspecialchars($arrstatus[$row['status']] ?? '-') ?></div></div>
        <div class="row-line"><div class="label">Entry Date</div><div class="value"><?= htmlspecialchars($row["entry_date"]) ?></div></div>

        <!-- MRP Button Added -->
      <div class="row-line">
  <div class="label">Action</div>
  <div class="value">
    <a href="<?= $scanLink ?>" class="btn btn-scan"><i class="fa fa-barcode"></i> Scan</a>
	      <a href="<?= $mrpLink ?>" class="btn btn-mrp"><i class="fa fa-tags"></i> MRP</a>
  </div>
</div>

      </div>
      <?php } ?>

      <div class="pagination">
        <?php if ($page > 1) { ?>
          <a href="?user_id=<?= urlencode($user_id) ?>&search=<?= urlencode($search) ?>&page=<?= $page - 1 ?>">Prev</a>
        <?php } ?>
        <?php for ($p = 1; $p <= $totalPages; $p++) { ?>
          <a href="?user_id=<?= urlencode($user_id) ?>&search=<?= urlencode($search) ?>&page=<?= $p ?>" class="<?= $p == $page ? 'active' : '' ?>"><?= $p ?></a>
        <?php } ?>
        <?php if ($page < $totalPages) { ?>
          <a href="?user_id=<?= urlencode($user_id) ?>&search=<?= urlencode($search) ?>&page=<?= $page + 1 ?>">Next</a>
        <?php } ?>
      </div>

      <?php
      } else {
        echo "<div class='alert alert-warning text-center'>No records found</div>";
      }
      ?>
    </div>
  </div>
</div>
<?php include("../../includes/connection_close.php"); ?>
</body>
</html>
