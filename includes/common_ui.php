<?php
function ajaxCall($tablename, $url, $data = [], $request_type = 'POST'){
        $dataJson = json_encode($data);
        return "
    <script>
    $(document).ready(function () {
        $('#{$tablename}').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
    url: '{$url}',
                type: '{$request_type}',
                data: function(d){
        let extraData = {$dataJson};
                    return $.extend({}, d, extraData);
                },
                error: function () {
        $('#{$tablename}').append(
            '<tbody><tr><td colspan=\"10\">No data found</td></tr></tbody>'
        );
    }
           }
        });
    });
    </script>";
}
function showToastUI($message = "", $type = "error"){

    $safeMessage = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');
    $bgColor = ($type === "success") ? "green" : "red";
    $toastId = "toast_" . rand(1000,9999); // unique id

    return '
    
    <div id="'.$toastId.'" class="toast" style="background: '.$bgColor.'; text-transform: capitalize;">
        <span class="message">'.$safeMessage.'</span>
    </div>

    <script>
        window.addEventListener("load", function() {
            const toast = document.getElementById("'.$toastId.'");

            if (toast) {
                setTimeout(() => {
                    toast.classList.add("show");
                }, 500);

                setTimeout(() => {
                    toast.classList.remove("show");
                }, 50000);
            }
        });
    </script>
    ';
}

function redirect($location='home2.php', $message = "", $type = "error", $data = []){

    $params = array_merge($data, [
        'type' => $type,
        'msg'  => urlencode($message)
    ]);
    $query = http_build_query($params);

    header("Location: {$location}?{$query}");
    exit;
}