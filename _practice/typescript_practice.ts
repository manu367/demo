import { readFileSync } from "node:fs";
const code = readFileSync("C:\\xampp1\\htdocs\\demo\\_practice\\test.txt", "utf8");
const fn = new Function("module", "exports", code);
const module = { exports: {} };
fn(module, module.exports);
const User: any = module.exports;
const u = new User("Manu");
console.log(u.greet());

enum ChartTypes {
    BAR="bar", LINE="line", PIE="pie", AREA="area", SCATTER="scatter", FUNAL="funal", GAUGE="gauge", COLUMN="column"
}
interface Chart{
    renderChart(chart:ChartTypes):Chart;
}
type ChartPropertie={charttype: string, x_axis:string, y_axis:string,data: string[]};
interface ChartProperties{
    chartPorperties(chart:Chart):ChartPropertie;
}

class ChartImplementation implements Chart{
    renderChart(chart: ChartTypes): Chart {
        if(chart===ChartTypes.AREA){
            return this;
        }
        return this;
    }
}
class ChartPropertiesImplementation implements ChartProperties{
    chartPorperties(chart: Chart): ChartPropertie {
        return {
            charttype:"",
            x_axis:"",
            y_axis:"",
            data:["","",""]
        };
    }

}
class GNode<T>{
    private _data:T;
    private _neghibour:GNode<T>[];
    public constructor(data:any) {
        this._data = data;
        this._neghibour=[];
    }

    get data(): T {
        return this._data;
    }

    set data(value: T) {
        this._data = value;
    }

    get neghibour(): GNode<T>[] {
        return this._neghibour;
    }

    set neghibour(value: GNode<T>[]) {
        this._neghibour = value;
    }
}
class GraphNode{
    private adjacncy:Map<number,GNode<number>>;
    public constructor(){
        this.adjacncy = new Map<number,GNode<number>>();
    }
    public addNode(data:number):void{
        const node = new GNode<number>(data);
        if(!this.adjacncy.has(data)){
            this.adjacncy.set(data,node);
        }
    }
    public addEdge(data1:number,data2:number):void{
        if(this.adjacncy.has(data1) && this.adjacncy.has(data2)){
            const node1:GNode<number>|undefined=this.adjacncy.get(data1);
            const node2:GNode<number>|undefined=this.adjacncy.get(data2);
            if(!node1 || !node2){
                throw new Error(`${data1} and ${data2} is not a valid graph node`);
            }
            node1.neghibour.push(node2);
            node2.neghibour.push(node1);
        }
    }

    public deleteNode(data: number): void {
        const nodeToDelete = this.adjacncy.get(data);
        if (!nodeToDelete) {
            throw new Error(`${data} node does not exist`);
        }
        nodeToDelete.neghibour.forEach((neighbourNode) => {
            neighbourNode.neghibour = neighbourNode.neghibour.filter(
                (n) => n.data !== data
            );
        });
        this.adjacncy.delete(data);
    }
}
/*
time table
english = part of speech ke paper , tense ke paper , active/passive , direct-indirect  = 20 questions (10marks)
Reasoning = Puzzles , Seating Arrangement , Syllogism , Blood Relation , Direction-distance , order ranking = 30 questions
Quant = DI , Profit/Loss , SI/CI , Time/work , Time/Speed/distance 20-25 questions
 */