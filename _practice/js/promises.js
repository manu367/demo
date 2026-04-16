function Observer(){}
Observer.prototype.notify=function(){
    throw new Error("Method not implemented.")
};
Observer.prototype.attach=function(){
    throw new Error("Method not implemented.")
};
Observer.prototype.detach=function(){
    throw new Error("Method not implemented.")
};
function Node(data) {
    this.data = data;
    this.next = null;
}
function LinkedList() {
    this.head = null;
}
LinkedList.prototype.add = function (data) {
    const newNode = new Node(data);
    if (this.head === null) {
        this.head = newNode;
        return;
    }
    let current = this.head;
    while (current.next !== null) {
        current = current.next;
    }
    current.next = newNode;
};
LinkedList.prototype.print = function () {
    let current = this.head;

    while (current !== null) {
        console.log(current.data);
        current = current.next;
    }
};

const list = new LinkedList();
list.add(12);
list.add(13);
list.add(14);
list.add(15);
list.add(16);
list.print();