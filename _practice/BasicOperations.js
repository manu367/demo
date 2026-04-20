/*
CRM APplication bana sakte hai with IVR System
1. Role Based Acceess | Permission = Observer Pattern ka use karna hai
2. Mutliple Users ACccess ( Admin , WH , Call )
3. IVR system | Chatbots options = JSON Format me data dekar question ke answer de sakte hai
                                   Specific user ke liye jitner permission hai utne he details de sakta hai ye
4. AI Applications

 */
const os = require('node:os');
const process = require('node:process');
const dns = require('dns');

function Observer(){
}
Observer.prototype.attach=function (subscriber){
    throw new Error("Method not implemented.");
}
Observer.prototype.notify=function (){
    throw new Error("Method not implemented.");
}
Observer.prototype.detach=function (subscriber){
    throw new Error("Method not implemented.");
}
function Subscriber(){}
Subscriber.prototype.update=function (ObserverData){
}

function FileUploadedObserver(){
    Observer.call(this);
    this.observer =[];
    this.files_data=[];
}
FileUploadedObserver.prototype=Object.create(Observer.prototype);
FileUploadedObserver.prototype.constructor=FileUploadedObserver;
FileUploadedObserver.prototype.attach=function (subscriber){
    this.observer.push(subscriber);
}
FileUploadedObserver.prototype.detach=function (subscriber){
    this.observer=this.observer.filter(sub=>{
        return sub!==subscriber;
    });
}
FileUploadedObserver.prototype.notify=function (){
    this.observer.forEach((subscriber)=>{
        subscriber.update(this.files_data);
    })
}
FileUploadedObserver.prototype.fileUplaodData=function(data){
    this.files_data=data;
}
function UrlUploadInDbSubScriber(){
    Subscriber.call(this);
}
UrlUploadInDbSubScriber.prototype=Object.create(Subscriber.prototype);
UrlUploadInDbSubScriber.prototype.constructor=UrlUploadInDbSubScriber;
UrlUploadInDbSubScriber.prototype.update=function(data){
    console.log(`/db/image/${data}`);
}
function URLSendtoUser(){
    Subscriber.call(this);
}
URLSendtoUser.prototype=Object.create(Subscriber.prototype);
URLSendtoUser.prototype.constructor=URLSendtoUser;
URLSendtoUser.prototype.update=function(data){
    console.log(`https://localhost:3306/images/${data}`);
}
function OtherOperationSubscriber(){
    Subscriber.call(this);
}
OtherOperationSubscriber.prototype=Object.create(Subscriber.prototype);
OtherOperationSubscriber.prototype.constructor=OtherOperationSubscriber;
OtherOperationSubscriber.prototype.update=function(data){
    console.log(`/otheroperations/${data}`);
}
function FileSizeChecker(size=0) {
    Subscriber.call(this);
    this.size=size;
}
FileSizeChecker.prototype=Object.create(Subscriber.prototype);
FileSizeChecker.prototype.constructor=FileSizeChecker;
FileSizeChecker.prototype.update=function(data){
    if(this.size>5){
        throw new Error("Bigger File Size Not Allowed");
    }
    console.log("File Size is less than 5");
}
function FileMetaDataStoreInDB(){
    Subscriber.call(this);
}
FileMetaDataStoreInDB.prototype=Object.create(Subscriber.prototype);
FileMetaDataStoreInDB.prototype.constructor=FileMetaDataStoreInDB;
FileMetaDataStoreInDB.prototype.update=function(data){
    const a=`
    Meta Data Stored In DB for ${data} file
    ID: ${Math.random()}; 
    Name : ${data} 
    filezie :${5} 
    ip      : ::${Math.random()} 
    data    : applicationtype/file
    `;
    console.log(a);
}

function HelloOpersetions(){
    Subscriber.call(this);
}
const fileuploadData=new FileUploadedObserver();
fileuploadData.attach(new FileSizeChecker(3));
fileuploadData.attach(new UrlUploadInDbSubScriber());
fileuploadData.attach(new URLSendtoUser());
fileuploadData.attach(new OtherOperationSubscriber());
fileuploadData.attach(new FileMetaDataStoreInDB());
fileuploadData.fileUplaodData("basicoperation.png");
try{
    fileuploadData.notify();
}catch (err){
    console.log(err.message);
}
