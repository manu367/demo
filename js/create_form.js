const table=document.getElementById("form_table");
const tbody=document.getElementById("addform");
console.log(tbody);
function counter(){
    let count=0;
    return function(){
        return count++;
    }
}
function rowCreate(param,displayname,option,length){
    const count=counter();
    let newRow = `<tr>
     <td>${count()}</td>
    <td><input type="text" name="param_name[]" class="form-control" value="${param}"></td>
    <td><input type="text" name="display_name[]" class="form-control" value="${displayname}"></td>
    <td>
        <select name="type[]" class="form-control">
            ${option};
        </select>
    </td>
    <td><input type="number" name="length[]" class="form-control" value="${length}"></td>
</tr>`;
    return row
}
function createRow(data={}) {
    if(data.length==0){
        console.log("empty");return;
    }
    console.log("sdcdc");
}