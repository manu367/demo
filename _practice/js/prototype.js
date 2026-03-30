const PERMISSION_VIEW     = 0x1;
const PERMISSION_WRITE    = 0x2;
const PERMISSION_REQUIRED = 0x4;
const PERMISSION_READ     = 0x8;

function Observer(){
    this._observers=[];
}
Observer.prototype.attach=function(subscriber){
    throw new Error("Method not implemented.");
}
Observer.prototype.disconnect=function(subscriber){
    throw new Error("Method not implemented.");
}
Observer.prototype.notify=function(){
    throw new Error("Method not implemented");
}
function Subscriber(){}
Subscriber.prototype.updated=function(data){
    throw new Error("Method not implemented.");
}

function PermissionObserver(){
    Observer.call(this);
    this.data=[];
}
PermissionObserver.prototype=Object.create(Observer.prototype);
PermissionObserver.prototype.constructor=PermissionObserver;

PermissionObserver.prototype.attach=function (subscriber){
    this._observers.push(subscriber);
}
PermissionObserver.prototype.disconnect=function (subscriber){
    this._observers=this._observers.filter((sub)=>{
        return sub!==subscriber;
    })
}
PermissionObserver.prototype.notify=function (){
    this._observers.forEach(subscriber=>{
        subscriber.updated(this.data);
    })
}
PermissionObserver.prototype.setData=function(data){
    this.data.push(data);
}
function FMSObserver(){
    Subscriber.call(this);
    this.permission=[];
}
FMSObserver.prototype=Object.create(Observer.prototype);
FMSObserver.prototype.constructor=FMSObserver;
FMSObserver.prototype.updated=function (data){
    this.permission=data;
    this.fmsView();
    this.fmsEdit();
    this.reportCreated();

}

//operation =>
FMSObserver.prototype.fmsView=function(){
    if(this.permission.includes(PERMISSION_VIEW)){
        console.log("PERMISSION_VIEW allowed");
    }else{
        console.log("PERMISSION_VIEW denied");
    }
}
FMSObserver.prototype.fmsEdit=function (){
    if(this.permission.includes(PERMISSION_WRITE)){
        console.log("PERMISSION_WRITE  allowed");
    }else{
        console.log("PERMISSION_WRITE denied");
    }
};
FMSObserver.prototype.reportCreated=function(){
    if(this.permission.includes(PERMISSION_REQUIRED)){
        console.log("Required Permission allowed");
    }else{
        console.log("Required Permission denied");
    }
}


const permission=new PermissionObserver();
const fms=new FMSObserver();
permission.attach(fms);
permission.setData(PERMISSION_READ);
permission.setData(PERMISSION_REQUIRED);
permission.notify();


