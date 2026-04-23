// proxy Pattern = subject , realSubject ,proxy
interface Charts{
    disPlayCharts():void;
}
interface  Observer{
    notify():void;
    attach(sub:SubScriber):void;
    detech(sub:SubScriber):void;
}
class PaymentObserver implements Observer{
    private observer:SubScriber[];
    private data:number|string|undefined;
    public constructor() {
        this.observer=[];
        this.data='';
    }
    attach(sub: SubScriber): void {
        this.observer.push(sub);
    }

    detech(sub: SubScriber): void {
        this.observer.filter((item=>{
            return item!==sub;
        }));
    }

    notify(): void {
        this.observer.forEach((item)=>{
            item.update(this.data);
        })
    }
    setData(data:number|string|undefined) {
        this.data=data;
        this.notify();
    }

}
interface SubScriber {
    update(data:number|string|undefined):void;
}
class PaymenetHandle implements SubScriber {
    update(data: number | string | undefined): void {
        console.log("PaymenetHandle update", data);
    }
}
class RecipitObserver implements SubScriber {
    update(data: number | string | undefined): void {
        console.log("Recipit send to the User", data);
    }
}
class MessageObserver implements SubScriber {
    update(data: number | string | undefined): void {
        console.log("Messae send to the User", data);
    }
}
class NotificationsObserver implements SubScriber {
    update(data: number | string | undefined): void {
        console.log("Notification send to the User", data);
    }
}
const payment=new PaymentObserver();
payment.attach(new PaymenetHandle());
payment.attach(new RecipitObserver());
payment.attach(new MessageObserver());
payment.attach(new NotificationsObserver());
payment.setData("{payment=1JHBSDC89239DSKB,NAME:'MANU PATHAK',PHONENUMER:6395896677}");