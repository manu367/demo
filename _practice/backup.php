<?php
require_once("../includes/config.php");
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
    <title><?=siteTitle?></title>
    <style>
        .success-box {
            display: flex;
            align-items: center;
            gap: 10px;
            background-color: #e6f9ec;
            border: 1px solid #28a745;
            color: #1e7e34;
            padding: 12px 16px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            width: fit-content;
            min-width: 250px;

            /* smooth entry */
            opacity: 0;
            transform: translateY(-5px);
            transition: all 0.3s ease;
        }

        .success-box.show {
            opacity: 1;
            transform: translateY(0);
        }

        .success-box .icon {
            font-weight: bold;
            font-size: 16px;
        }
    </style>
    <script>
        function Programming(){
            this.la
        }
    </script>
</head>
<body>



<div class="container-fluid">
    <div class="row content">
        <?php include("../includes/leftnav2.php"); ?>
        <div class="<?=$screenwidth?>">
            <div id="successMessage" class="success-box show">
                <span class="icon">✔</span>
                <span class="text">Data saved successfully</span>
            </div>
            <script>
                function showSuccess(message = "Saved successfully") {
                    const box = document.getElementById("successMessage");
                    box.querySelector(".text").innerText = message;

                    box.classList.add("show");

                    // Auto hide (optional)
                    setTimeout(() => {
                        box.classList.remove("show");
                    }, 4000);
                }
            </script>
        </div>
    </div>
</div>
</div>

<div id="snackbar">This is a snackbar message</div>

<?php
include("../includes/footer.php");
include("../includes/connection_close.php");
?>
<div id="typeOptions" style="display:none;">
    <?=$option?>
</div>
<div id="customAlertContainer"></div>
<script>
    function counterLe(value = '') {
        let count;
        if (value === '' || value === null || value === undefined) {
            count = 2;
        } else {
            const num = Number(value);
            if (isNaN(num)) {
                console.warn("Invalid value passed, defaulting to 2");
                count = 2;
            } else {
                count = num;
            }
        }
        return function () {
            return count++;
        };
    }

    document.addEventListener("DOMContentLoaded",function(){
        let count=counterLe('<?=$countleave===0?2:$countleave?>');
        $("#row").click(function () {
            let i=count();

            let options = $("#typeOptions").html(); // 🔥 yaha se uthao
            console.log(options);

            let newRow = `<tr>
     <td>${i}</td>
    <td><input type="text" name="param_name[]" class="form-control"></td>
    <td><input type="text" name="display_name[]" class="form-control"></td>
    <td>
        <select name="type[]" class="form-control">
            <option>-Select option-</option>
            ${options}
        </select>
    </td>
    <td><input type="number" name="length[]" class="form-control"></td>
<td class='text-center'><input type="hidden" name="check[]" value="0">
    <input type="checkbox" class="check_box_hidden" value="1">
</td>
</tr>`;
            checkboxvalue();
            $("#addform").append(newRow);
        });

        const form1=document.getElementById("frm1");
        form1.addEventListener("submit", function (e) {
            let isValid=true;
            const frm_name=document.getElementById("frm_name").value.trim();
            const fmsid=document.getElementById("fmsid").value.trim();
            const formid=document.getElementById("formid").value.trim();
            if(frm_name === null || frm_name === ""){
                showAlert("Please Fill Form Name", "error");
            }
            const param = document.querySelectorAll('input[name="param_name[]"]');
            const displayname=document.querySelectorAll("input[name='display_name[]']");
            const typeselect=document.querySelectorAll("select[name='type[]']");
            const inputlength=document.querySelectorAll("input[name='length[]']");
            param.forEach((input, index) => {
                if (input.value.trim() === "") {
                    showAlert(`Please Fill  Name ${index + 1}`, "error");
                    input.focus();
                    isValid = false;
                    return;
                }
            });

            displayname.forEach((input,index)=>{
                if(input.value.trim() === ""){
                    showAlert(`Disply Name must be enter ${index + 1}`, "error");
                    input.focus();
                    isValid = false;
                }
            });

            typeselect.forEach((input, index) => {
                if(input.value==='-Select option-'){
                    showAlert(`Please Select input type ${index + 1}`, "error");
                    input.focus();
                    isValid = false;
                }
            });

            inputlength.forEach((input, index) => {
                if (!input.value) {
                    showAlert(`input length Must be Enter ${index + 1}`, "error");
                    input.focus();
                    isValid = false;
                }
            });

            if(!isValid){
                e.preventDefault();
            }
        });
    });

    // showAlert("Form submitted successfully!", "success");
    // showAlert("Something went wrong!", "error");
    // showAlert("Check your input!", "warning");

    function showAlert(message, type = "success", duration = 3000) {
        const container = document.getElementById("customAlertContainer");
        const alert = document.createElement("div");
        alert.classList.add("alert-box", `alert-${type}`);
        alert.innerHTML = `
        <span>${message}</span>
        <span class="close-btn">&times;</span>
    `;
        container.appendChild(alert);
        alert.querySelector(".close-btn").addEventListener("click", () => {
            alert.remove();
        });
        setTimeout(() => {
            alert.remove();
        }, duration);
    }
</script>

<script>
    function checkboxvalue(){
        document.querySelectorAll(".check_box_hidden").forEach((checkbox)=>{

            let hidden = checkbox.previousElementSibling;
            hidden.value = checkbox.checked ? 1 : 0;
            checkbox.addEventListener("change", function(){
                hidden.value = this.checked ? 1 : 0;
            });
        });
    }
    checkboxvalue();
</script>
<script>
    document.querySelectorAll("input").forEach((cell) => {
        cell.style.textTransform = "capitalize";
    });
    document.querySelectorAll("select").forEach((cell) => {
        cell.style.textTransform = "capitalize";
    });




    const selectbox="<?=showDropDown_master($link1)?>";

    document.addEventListener("change", function(e){
        if (e.target.matches("select")) {
            if (e.target.value === '8') {
                const td = e.target.parentElement.nextElementSibling;
                const input = td.querySelector("input");

                if (input) {
                    input.value = 50;
                    input.style.display = "none";
                }

                td.insertAdjacentHTML("beforeend", `<?=$selectedBox?>`);
            }
        }
    });

</script>
<script id="a1b2c3">
    document.addEventListener("input", function(e){
        if(e.target.matches("input[name='param_name[]']")){
            let query = e.target.value.trim();
            if(query.length < 2){
                removeSuggestionBox(e.target);
                return;
            }
            fetch(`../pagination/table-column-data.php?formid=<?=$res['id']?>&fmsid=<?=$load['id']?>&q=${query}`)
                .then(res => res.json())
                .then(res => {
                    if(res.status){
                        showSuggestions(e.target, res.data);
                    }
                });
        }
    });
    function showSuggestions(input, data){
        removeSuggestionBox(input); // clean old
        let box = document.createElement("div");
        box.classList.add("suggestion-box");
        data.forEach(item => {
            let div = document.createElement("div");
            div.textContent = item;
            div.classList.add("suggestion-item");
            div.addEventListener("click", function(){
                input.value = item;
                removeSuggestionBox(input);
            });
            box.appendChild(div);
        });
        input.parentElement.appendChild(box);
    }
    function removeSuggestionBox(input){
        let old = input.parentElement.querySelector(".suggestion-box");
        if(old) old.remove();
    }
</script>
<script>
    let invalidInputs = new Set();
    document.addEventListener("input", function(e) {
        if (e.target.matches("input[name='param_name[]']")) {
            ParamtertypeFetch(e.target)
        }
    });

    async function ParamtertypeFetch(input){
        const value = input.value.trim();
        const normalizedValue = normalize(value);

        let url = `../pagination/table-column-data.php?fms_id=<?=$load['id']?>&formid=<?=$res['id']?>&column=${value}`;

        const response = await fetch(url);
        const data = await response.json();
        console.log(data);
        let isDuplicate = false;

        data.forEach(item => {
            if (normalize(item) === normalizedValue){
                isDuplicate = true;
            }
        });

        if(isDuplicate){
            input.style.border = "2px solid red";
            invalidInputs.add(input);
            showSnackbar(input, `${value.toUpperCase()} already exists`);
        } else {
            input.style.border = "2px solid green";
            invalidInputs.delete(input);
        }
    }

    document.querySelector("form").addEventListener("submit", function(e){
        if(invalidInputs.size > 0){
            e.preventDefault();
            showSnackbar(null, "Fix errors before submitting");
        }
    });

    function normalize(str) {
        return str
            .toLowerCase()
            .replace(/[\s_]/g, ''); // remove space + underscore
    }


    function showSnackbar(input=null,msg='') {
        if(input!==null){
            input.style.border="2px solid red";
        }
        const snackbar = document.getElementById("snackbar");
        snackbar.textContent = msg;
        snackbar.classList.add("show");

        setTimeout(() => {
            snackbar.classList.remove("show");
        }, 3000);
    }
</script>
<script>
    /*
         ek condition (shart) hoti hai aur uska result.
         Types of Condonitional Sentences
         1. Zero Conditional Sentences
         2. First Conditional Sentences
         3. Second Conditional
         4. Third Conditional

     */
</script>
</body>
</html>