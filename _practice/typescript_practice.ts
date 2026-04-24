/*
    User Inputs
       |
--------------------------------------
|  Data Source (Store all data)       |
|        |                            |
|  validator to check data            |
|       |                             |
|     render Chart with data          |
|      |                              |
|   re-render charts                  |
|                                     |
---------------------------------------
 */
interface Observer{
    attach(sub:Subscriber):void;
    detach(sub:Subscriber):void;
    setData(data:any):void;
    notify():void;
}
interface Subscriber{
    update(data:any):void;
}
class ChartObserver implements Observer{
    private observer:Subscriber[];
    private data:any;
    public constructor() {
        this.observer=[];
        this.data=null;
    }
    attach(sub: Subscriber): void {
        this.observer.push(sub);
    }

    detach(sub1: Subscriber): void {
        this.observer=this.observer.filter(sub=>{
            return sub!==sub1;
        });
    }

    notify(): void {
        this.observer.forEach((sub:Subscriber)=>{
            sub.update(this.data)
        });
    }

    setData(data: any): void {
        this.data=data;
    }
}

class ChartObserverSubscripber implements Subscriber{
    update(data: any): void {
        const set={
            charttype:data.charttype??'line',
            chartName:data.chartname??'Null value',
            chartSubName:data.chartSubName??'Null value',
            XParamerName:data.xParamerName??'Null value',
            YParamerName:data.yParamerName??'Null value',
            aligment:data.aligment??'center',
        }
        const datasurce:DataSource=new DataSource(set);
    }
}


interface DataSetProps{
    charttype:string;
    chartName:string;
    chartSubName:string;
    XParamerName:string;
    YParamerName:string;
    aligment:string;
}
class DataSource{
    private _charttype:string;
    private _chartName:string;
    private _chartSubName:string;
    private _XParamerName:string;
    private _YParamerName:string;
    private _aligment:string;
    constructor(props:DataSetProps) {
        this._charttype=props.charttype;
        this._chartName=props.chartName;
        this._chartSubName=props.chartSubName;
        this._XParamerName=props.XParamerName;
        this._YParamerName=props.YParamerName;
        this._aligment=props.aligment;
    }

    get charttype(): string {
        return this._charttype;
    }

    set charttype(value: string) {
        this._charttype = value;
    }

    get chartName(): string {
        return this._chartName;
    }

    set chartName(value: string) {
        this._chartName = value;
    }

    get chartSubName(): string {
        return this._chartSubName;
    }

    set chartSubName(value: string) {
        this._chartSubName = value;
    }

    get XParamerName(): string {
        return this._XParamerName;
    }

    set XParamerName(value: string) {
        this._XParamerName = value;
    }

    get YParamerName(): string {
        return this._YParamerName;
    }

    set YParamerName(value: string) {
        this._YParamerName = value;
    }

    get aligment(): string {
        return this._aligment;
    }

    set aligment(value: string) {
        this._aligment = value;
    }
}
