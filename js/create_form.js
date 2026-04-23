function LNode(data){
    this.data=data;
}
function ConnectionStablish(){
    this.connection=new Map();
}
ConnectionStablish.prototype.addNode=function(key,node){
    if(!this.connection.has(key)){
        this.connection.add(key,new LNode(node));
    }
}
ConnectionStablish.prototype.deleteNode=function(key,node){
    if(this.connection.has(key)){
        this.connection.delete(key);
    }
}
ConnectionStablish.prototype.getConnection=function(key){
    if(!this.connection.has(key)){
        return this.connection.get(key);
    }
    return null;
}

ConnectionStablish.prototype.checkConnection=function(iput,input){
    if(this.connection.has(key)){
        const firstinput=this.connection.get(iput);
        const forminput=this.connection.get(input);
        if(!firstinput){
            throw new Error('Some things is wrong');
        }
        if(!forminput){
            throw new Error('Something is wrong');
        }
    }
}

function DupblicationRemover(){
    this.old_col=null;
    this.dbCol=null;
    this.invalidInput=new Set();
    this.error=new Set();
    this.connection=new ConnectionStablish();
}

DupblicationRemover.prototype.dbParamterFetch=async function(){
    let url = `../pagination/table-column-data.php?fms_id=<?=$load['id']?>&formid=<?=$res['id']?>&column=${''}`;
    const response = await fetch(url);
    const data = await response.json();
    // this.old_col=data;
    this.dbCol=await data;
}

DupblicationRemover.prototype.noromilizeFun=function(str){
    return str
        .toLowerCase()
        .replace(/\s+/g, '_'); // space -> underscore ( Candour Software => Candour_software)
}

DupblicationRemover.prototype.showSnackbar= function(input=null,msg='') {
    if(input!==null){
        input.style.border="2px solid red";
    }
    const snackbar = document.getElementById("snackbar");
    snackbar.textContent = msg;
    snackbar.classList.add("show");
    setTimeout(() => {
        snackbar.classList.remove("show");
    }, 5000);
}

DupblicationRemover.prototype.stopFormSubmit = function(){
    const self = this;
    document.querySelector("form").addEventListener("submit", function(e){
        if(self.error.size > 0){
            e.preventDefault();
            self.showSnackbar(null, "Fix errors before submitting");
        }
    });
}

DupblicationRemover.prototype.loadAllTable=function(){}

DupblicationRemover.prototype.addForm=function (){
    if(this.dbCol!==null){
        this.dbCol.forEach((col)=>{
            this.invalidInput.add(col);
        });
    }
    // console.log(this.invalidInput);
    const set=this.invalidInput;
    const error=this.error;
    document.addEventListener("input", function(e) {
        if (e.target.matches("input[name='param_name[]']")) {
            // Remove special characters (keep letters + numbers only)
            let cleanValue = e.target.value.replace(/[^a-zA-Z0-9 ]/g, '');
            e.target.value = cleanValue;
            let value=DupblicationRemover.prototype.noromilizeFun(e.target.value);
            value=value.trim();
            if(set.has(value)){
                error.add(e.target);
                DupblicationRemover.prototype.showSnackbar(e.target,`Already exists ${e.target.value} in the table`);
                DupblicationRemover.prototype.stopFormSubmit();
            }
            else{
                error.delete(e.target);
                e.target.style.border="0px solid";
            }
        }
    });
}
DupblicationRemover.prototype.updateForm = function(){
    if(this.dbCol !== null){
        this.dbCol.forEach((col)=>{
            this.invalidInput.add(this.noromilizeFun(col));
        });
    }
    let old_col = document.getElementById("old_column");
    let old_col_value = JSON.parse(JSON.parse(old_col.value));
    const set = this.invalidInput;
    const error = this.error;
    const self = this;
    document.addEventListener("input", function(e){
        if (e.target.matches("input[name='param_name[]']")) {
            // Remove special characters (keep letters + numbers only)
            let cleanValue = e.target.value.replace(/[^a-zA-Z0-9 ]/g, '');
            e.target.value = cleanValue;
            let element = e.target;
            element.value = cleanValue; // ishke pass ab clean value h
            const newValue = self.noromilizeFun(element.value.trim());
            const oldValue = self.noromilizeFun(element.dataset.old ?? '');
            //case 1: same as old => always valid (manu_pathak = [manu_pathak]=old_col)
            if(newValue === oldValue){
                error.delete(element);
                element.style.border = "1px solid #ccc";
                return;
            }
            // case 2: changed but already exists in DB - error
            if(set.has(newValue)){
                error.add(element);
                self.showSnackbar(element, `Already exists ${e.target.value} in the table`);
                element.style.border = "2px solid red";
            }
            //  case 3: changed and unique -> valid
            else{
                error.delete(element);
                element.style.border = "1px solid #ccc";
            }
        }
        console.log([...error]);
    });
};
document.addEventListener("DOMContentLoaded", async function(){
    const operations="<?=isset($_REQUEST['op'])?$_REQUEST['op']:''?>";
    const validation=new DupblicationRemover();
    await validation.dbParamterFetch();

    if(operations===''){
        validation.addForm();
    }else{
        console.log(validation.dbCol);
        validation.updateForm();
    }
    validation.stopFormSubmit();
});