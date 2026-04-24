function GetAllChartData(){
    this.url="../pagination/chart-data_grid.php?chart=%27%27";
    this.data=null;
}
GetAllChartData.prototype.fetchData=async function(){
    const response = await fetch(this.url);
    const data = await response.json();
    return data;
}
GetAllChartData.prototype.setData=function(data){
    this.data=data;
}

// 🔹 Global state
let dataSet = {
    chartype: "area",
    title: "this is manu",
    subtitle: "hello brow",
    x_paramterName: "manu pathak",
    y_paramterName: "pathak manu",
    alignement: "left"
};



// 🔹 Observer
function GuiObserver(){
    this.observer = [];
    this.data = null;
}

GuiObserver.prototype.attach = function(el){
    this.observer.push(el);
}

GuiObserver.prototype.setData = function(data){
    this.data = data;
    this.notifyData(); // 🔥 important
}

GuiObserver.prototype.notifyData = function(){
    this.observer.forEach((subscriber) => {
        subscriber.update(this.data);
    });
}



// 🔹 Data Wrapper
function DataWrapper(){
    this.charttype = 'area';
    this.title = "";
    this.subTitle = "";
    this.x_paramterName = "";
    this.y_paramterName = "";
    this.alignement = "center";
}

DataWrapper.prototype.setAll = function(data){
    this.charttype = data.chartype;
    this.title = data.title;
    this.subTitle = data.subtitle;
    this.x_paramterName = data.x_paramterName;
    this.y_paramterName = data.y_paramterName;
    this.alignement = data.alignement;
}



// 🔹 ActionTaker (Subscriber)
function ActionTaker(){
    this.wrapper = new DataWrapper();
}

ActionTaker.prototype.update = function(data){
    this.wrapper.setAll(data);
    loadScript(this.wrapper);
}



// 🔹 Chart Loader
let chartInstance = null;

function loadScript(wrapper){

    // 🔥 pehli baar create
    if(!chartInstance){
        chartInstance = Highcharts.chart('container', {
            chart: {
                type: wrapper.charttype
            },
            title: {
                text: wrapper.title,
                align: wrapper.alignement
            },
            subtitle: {
                text: wrapper.subTitle,
                align: wrapper.alignement
            },
            xAxis: {
                title: {
                    text: wrapper.x_paramterName
                }
            },
            yAxis: {
                title: {
                    text: wrapper.y_paramterName
                }
            },
            series: [{
                name: 'Demo',
                data: [1,2,3,4,5]
            }]
        });

    } else {
        // 🔥 update instead of recreate
        chartInstance.update({
            chart: { type: wrapper.charttype },
            title: {
                text: wrapper.title,
                align: wrapper.alignement
            },
            subtitle: {
                text: wrapper.subTitle,
                align: wrapper.alignement
            },
            xAxis: {
                title: { text: wrapper.x_paramterName }
            },
            yAxis: {
                title: { text: wrapper.y_paramterName }
            }
        });
    }
}



// 🔹 UI Handler
function RenderChartData(){
    this.chartype = document.getElementById("charttype");
    this.charttitle = document.getElementById("charttitle");
    this.xParamterName = document.getElementById("x_axis_label");
    this.yParamterName = document.getElementById("y_axis_label");
    this.alignment = document.getElementById("chart_alignment");
}


// 🔹 Dropdown fill
RenderChartData.prototype.setChartType = function(type){
    this.chartype.innerHTML += `<option value="${type}">${type}</option>`;
}


// 🔹 Events bind
RenderChartData.prototype.bindEvents = function(observer){

    this.chartype.addEventListener("change", (e)=>{
        dataSet.chartype = e.target.value;
        observer.setData({...dataSet});
    });

    this.charttitle.addEventListener("input", (e)=>{
        dataSet.title = e.target.value;
        observer.setData({...dataSet});
    });

    this.xParamterName.addEventListener("input", (e)=>{
        dataSet.x_paramterName = e.target.value;
        observer.setData({...dataSet});
    });

    this.yParamterName.addEventListener("input", (e)=>{
        dataSet.y_paramterName = e.target.value;
        observer.setData({...dataSet});
    });

    this.alignment.addEventListener("change", (e)=>{
        dataSet.alignement = e.target.value;
        observer.setData({...dataSet});
    });
}

document.addEventListener("DOMContentLoaded", async function(){

    const chartAPI = new GetAllChartData();
    const render = new RenderChartData();
    const observer = new GuiObserver();
    const action = new ActionTaker();
    observer.attach(action);
    const data = await chartAPI.fetchData();
    chartAPI.setData(data);

    chartAPI.data.forEach((item)=>{
        render.setChartType(item.chart_name);
    });
    render.bindEvents(observer);
    observer.setData(dataSet);
});