<?php
require_once("../includes/config.php");
global $link1;
if(isset($_REQUEST['formid'])){
    $formid = $_REQUEST['formid'];
    $form=new FormView($link1);
    $data=$form->loadform($formid);
    var_dump($data);exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link href="../css/font-awesome.min.css" rel="stylesheet">

    <title>Modern Form UI</title>

    <style>
        body {
            background: linear-gradient(135deg, #667eea, #764ba2);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', sans-serif;
        }

        .form-card {
            background: #fff;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 450px;
            animation: fadeInUp 0.8s ease;
            transition: all 0.3s ease;
        }

        .form-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 25px 60px rgba(0,0,0,0.25);
        }

        .form-title {
            text-align: center;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .form-control {
            border-radius: 10px;
            padding: 12px;
            transition: 0.3s;
        }

        .form-control:focus {
            box-shadow: 0 0 10px rgba(102,126,234,0.5);
            border-color: #667eea;
        }

        .btn-custom {
            background: #667eea;
            color: #fff;
            border-radius: 10px;
            padding: 12px;
            transition: 0.3s;
        }

        .btn-custom:hover {
            background: #5a67d8;
            transform: scale(1.05);
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 576px) {
            .form-card {
                padding: 20px;
            }
        }
    </style>
</head>

<body>

<div class="form-card">

    <h3 class="form-title"><?=$data['form_name']?> form</h3>

    <form>

        <?=$form->viewFrom($data)?>
        <button type="submit" class="btn btn-custom w-100">Submit</button>
    </form>

</div>


</body>
</html>