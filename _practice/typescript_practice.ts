class GraphNode {
    private data: string | number;
    private neighbors: GraphNode[];

    public constructor(data: string | number) {
        this.data = data;
        this.neighbors = [];
    }

    public addNeighbor(node: GraphNode) {
        this.neighbors.push(node);
    }

    public getNeighbors(): GraphNode[] {
        return this.neighbors;
    }

    public getData(): string | number {
        return this.data;
    }
}

class GraphDataStructure {
    public adjacency: Map<number, GraphNode>;

    public constructor() {
        this.adjacency = new Map<number, GraphNode>();
    }

    public addNode(data: number) {
        if (!this.adjacency.has(data)) {
            this.adjacency.set(data, new GraphNode(data));
        }
    }
    //https://t.me/radhika17777

    public addEdges(data1: number, data2: number) {
        const node1 = this.adjacency.get(data1);
        const node2 = this.adjacency.get(data2);

        if (node1 && node2) {
            node1.addNeighbor(node2);
            node2.addNeighbor(node1); // undirected graph
        }
    }
    public bfs(start: number): (string | number)[] {
        const startNode = this.adjacency.get(start);
        if (!startNode) return [];

        const visited = new Set<GraphNode>();
        const queue: GraphNode[] = [];
        const result: (string | number)[] = [];

        queue.push(startNode);
        visited.add(startNode);
        console.log(visited);;
        while (queue.length > 0) {
            const current = queue.shift()!;
            result.push(current.getData());

            for (const neighbor of current.getNeighbors()) {
                if (!visited.has(neighbor)) {
                    visited.add(neighbor);
                    queue.push(neighbor);
                }
            }
        }

        return result;
    }
}

const graph=new GraphDataStructure();
graph.addNode(12);graph.addNode(13);
graph.addNode(14);graph.addNode(15);
graph.addNode(16);graph.addNode(17);

graph.addEdges(12,13);
graph.addEdges(13,14);
graph.addEdges(14,15);
graph.addEdges(15,16);
graph.addEdges(16,17);
graph.addEdges(17,12);

console.log(graph.bfs(12));

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

