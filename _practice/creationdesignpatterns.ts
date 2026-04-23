/*
Creations Patterns
-> Singleton  = complete application ke liye singleton
-> Factory  = singe object factory pattern
-> Builder =
-> prototype = object cloneing
-> Adstract = jab reuses component and indpendecly ke liye use karte hai
 */


class UserModel{
    private name:string;
    private phoneNumber:number;
    public constructor(builder:UserModelBuilder) {
        this.name=builder.name
        this.phoneNumber=builder.phoneNumber;
    }
    public static builder(){
        return new UserModelBuilder()
    }
    public static creatBuilder(builder:UserModelBuilder){
        return new UserModel(builder);
    }
}
class UserModelBuilder{
    private _name:string;
    private _phoneNumber:number;
    public constructor(){
        this._name='';
        this._phoneNumber=0;
    }

    get name(): string {
        return this._name;
    }

    public setName(name:string){
        this._name = name;
        return this;
    }

    get phoneNumber(): number {
        return this._phoneNumber;
    }

    public setPhoneNumber(phoneNumber:number){
        this._phoneNumber=phoneNumber;
        return this;
    }
    build(){
        return UserModel.creatBuilder(this);
    }
}
const user=UserModel.builder().setName("this is manu patha")
    .setPhoneNumber(12345789)
    .build();
console.log(user);

interface Payment{
    paymentID_Generations():boolean;
    payementId():number;
    pay():void;
    isSuccessful():boolean;
    sendRecipients(payemntid:number):void;
}
interface PaymentHandler{
    makePayment(payment:Payment):void;
}
class PaymentHandlerImplementation implements PaymentHandler{
    makePayment(payment: Payment): string {
        if(payment.paymentID_Generations()){
            payment.pay();
        }
        if(!payment.isSuccessful()){
            throw new Error("Payment was not successfully signed up");
        }
        payment.sendRecipients(payment.payementId())
        return "Succefully Payement"
    }
}