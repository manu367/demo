"use strict";
let __loaderEl = null;
function createLoader() {
    const wrapper = document.createElement("div");
    wrapper.id = "global-loader";
    wrapper.className = `
        fixed inset-0 z-[9999] flex items-center justify-center
        bg-black/40 backdrop-blur-sm hidden
    `;
    wrapper.innerHTML = `
        <div class="flex flex-col items-center gap-3">
            <div class="w-12 h-12 border-4 border-blue-500 border-t-transparent rounded-full animate-spin"></div>
            <p class="text-white text-sm tracking-wide">Loading...</p>
        </div>
    `;
    document.body.appendChild(wrapper);
    return wrapper;
}
function loader(action = "show") {
    if (!__loaderEl) {
        __loaderEl = createLoader();
    }
    if (action === "show") {
        __loaderEl.classList.remove("hidden");
    }
    else {
        __loaderEl.classList.add("hidden");
    }
}
function generateDataBasedOnChartType(chartType) {
}
class DataWrapper {
    _chartSubtitle;
    _xAxisParam;
    _yAxisParam;
    _chartTitle;
    _charttype;
    _xAxisLabel;
    _yAxisLabel;
    _fromDate;
    _toDate;
    _filterMonth;
    _chartAlignment;
    _chartColor;
    _data;
    constructor() {
        this._chartSubtitle = "";
        this._xAxisParam = "";
        this._yAxisParam = "";
        this._chartTitle = "";
        this._charttype = "area"; // default
        this._xAxisLabel = "";
        this._yAxisLabel = "";
        this._fromDate = "";
        this._toDate = "";
        this._filterMonth = "";
        this._chartAlignment = "center";
        this._chartColor = "#3b82f6";
        this._data = null;
    }
    get data() {
        return this._data;
    }
    set data(value) {
        this._data = value;
    }
    get chartSubtitle() {
        return this._chartSubtitle;
    }
    set chartSubtitle(value) {
        this._chartSubtitle = value;
    }
    get xAxisParam() {
        return this._xAxisParam;
    }
    set xAxisParam(value) {
        this._xAxisParam = value;
    }
    get yAxisParam() {
        return this._yAxisParam;
    }
    set yAxisParam(value) {
        this._yAxisParam = value;
    }
    get chartTitle() {
        return this._chartTitle;
    }
    set chartTitle(value) {
        this._chartTitle = value;
    }
    get charttype() {
        return this._charttype;
    }
    set charttype(value) {
        this._charttype = value;
    }
    get xAxisLabel() {
        return this._xAxisLabel;
    }
    set xAxisLabel(value) {
        this._xAxisLabel = value;
    }
    get yAxisLabel() {
        return this._yAxisLabel;
    }
    set yAxisLabel(value) {
        this._yAxisLabel = value;
    }
    get fromDate() {
        return this._fromDate;
    }
    set fromDate(value) {
        this._fromDate = value;
    }
    get toDate() {
        return this._toDate;
    }
    set toDate(value) {
        this._toDate = value;
    }
    get filterMonth() {
        return this._filterMonth;
    }
    set filterMonth(value) {
        this._filterMonth = value;
    }
    get chartAlignment() {
        return this._chartAlignment;
    }
    set chartAlignment(value) {
        this._chartAlignment = value;
    }
    get chartColor() {
        return this._chartColor;
    }
    set chartColor(value) {
        this._chartColor = value;
    }
}
class ChartExposeSubscriber {
    update(data) {
        SingleTonInstanceHightChart.renderChart(SingleTonInstanceHightChart.instance, data);
    }
}
function getSeriesByChartType(type, color) {
    const baseData = [10, 20, 30, 40, 50];
    switch (type) {
        case "scatter":
            return [{
                    type: "scatter",
                    data: baseData.map((y, i) => [i, y]),
                    color: color
                }];
        case "funnel":
            return [{
                    type: "funnel",
                    data: [
                        ["Stage 1", 100],
                        ["Stage 2", 80],
                        ["Stage 3", 50]
                    ],
                    color: color
                }];
        case "gauge":
            return [{
                    type: "gauge",
                    data: [70],
                    color: color
                }];
        default:
            return [{
                    type: type,
                    data: baseData,
                    color: color
                }];
    }
}
class InputObserver {
    observer;
    data;
    constructor() {
        this.observer = [];
        this.data = new DataWrapper();
    }
    attach(sub) {
        this.observer.push(sub);
    }
    distach(sub) {
        this.observer = this.observer.filter((item) => {
            return item !== sub;
        });
    }
    notify() {
        this.observer.forEach((item) => {
            item.update(this.data);
        });
    }
    setData(data) {
        this.data = data;
        this.notify();
    }
}
class FormUnits {
    chartSubtitle;
    xAxisParam;
    yAxisParam;
    chartTitle;
    charttype;
    xAxisLabel;
    yAxisLabel;
    fromData;
    toData;
    filterMonth;
    chartAlignment;
    chartColor;
    group_operations;
    constructor() {
        this.charttype = document.getElementById("charttype");
        this.chartTitle = document.getElementById("charttitle");
        this.chartSubtitle = document.getElementById("chartsubtitle");
        this.xAxisLabel = document.getElementById("x_axis_label");
        this.yAxisLabel = document.getElementById("y_axis_label");
        this.xAxisParam = document.getElementById("x_axis_param");
        this.yAxisParam = document.getElementById("y_axis_param");
        this.fromData = document.getElementById("from_date");
        this.toData = document.getElementById("to_date");
        this.filterMonth = document.getElementById("filter_month");
        this.chartAlignment = document.getElementById("chart_alignment");
        this.chartColor = document.getElementById("chart_color");
        this.group_operations = document.getElementById("group_operations");
    }
    async bindInputData(observer) {
        const chartoperations = new ChartOperations();
        const datawrapper = new DataWrapper();
        const validateParams = () => {
            const xVal = this.xAxisParam.value;
            const yVal = this.yAxisParam.value;
            if (xVal && yVal && xVal === yVal) {
                alert("X and Y axis cannot be same");
                return false;
            }
            return true;
        };
        this.charttype.addEventListener("change", async () => {
            datawrapper.charttype = await this.charttype.value.trim();
            observer.setData(datawrapper);
            let response = await chartoperations.chartOperations(datawrapper.charttype);
            if (response.status) {
                chartoperations.renderOptionMenus(this.group_operations, response.data);
            }
            else {
                chartoperations.renderOptionMenus(this.group_operations, []);
            }
        });
        this.chartTitle.addEventListener("input", () => {
            const clean = this.sanitizeInput(this.chartTitle.value);
            this.chartTitle.value = clean; // 🔥 UI update
            datawrapper.chartTitle = clean.toUpperCase();
            observer.setData(datawrapper);
        });
        this.chartSubtitle.addEventListener("input", () => {
            datawrapper.chartSubtitle = this.sanitizeInput(this.chartSubtitle.value.trim().toUpperCase());
            observer.setData(datawrapper);
        });
        this.xAxisLabel.addEventListener("input", () => {
            datawrapper.xAxisLabel = this.sanitizeInput(this.xAxisLabel.value.trim().toUpperCase());
            observer.setData(datawrapper);
        });
        this.yAxisLabel.addEventListener("input", () => {
            datawrapper.yAxisLabel = this.yAxisLabel.value.trim().toUpperCase();
            observer.setData(datawrapper);
        });
        this.xAxisParam.addEventListener("change", () => {
            if (!validateParams()) {
                this.xAxisParam.value = "";
                return;
            }
            datawrapper.xAxisParam = this.xAxisParam.value;
            observer.setData(datawrapper);
        });
        this.yAxisParam.addEventListener("change", () => {
            if (!validateParams()) {
                this.yAxisParam.value = "";
                return;
            }
            datawrapper.yAxisParam = this.yAxisParam.value;
            observer.setData(datawrapper);
        });
        this.chartAlignment.addEventListener("change", () => {
            datawrapper.chartAlignment = this.chartAlignment.value;
            observer.setData(datawrapper);
        });
        this.chartColor.addEventListener("input", () => {
            datawrapper.chartColor = this.chartColor.value;
            observer.setData(datawrapper);
        });
    }
    validateField(el) {
        const value = el.value.trim();
        if (!value) {
            el.classList.add("border-red-500", "ring-red-500");
            el.classList.remove("border-gray-600");
            return false;
        }
        el.classList.remove("border-red-500", "ring-red-500");
        el.classList.add("border-gray-600");
        return true;
    }
    sanitizeInput(value) {
        return value.replace(/[^a-zA-Z0-9 ]/g, "");
    }
    validateAll() {
        let isValid = true;
        const fields = [
            this.charttype,
            this.chartTitle,
            this.chartSubtitle,
            this.xAxisLabel,
            this.yAxisLabel,
            this.xAxisParam,
            this.yAxisParam,
            this.group_operations
        ];
        fields.forEach((field) => {
            if (!this.validateField(field)) {
                isValid = false;
            }
        });
        return isValid;
    }
}
document.addEventListener("DOMContentLoaded", async function () {
    const formunit = new FormUnits();
    const observer = new InputObserver();
    observer.attach(new ChartExposeSubscriber());
    formunit.bindInputData(observer);
    // loader('show');
    // setTimeout(()=>{
    //     loader('hide');
    // },3000);
    document.getElementById("form1")?.addEventListener("submit", function (event) {
        if (!formunit.validateAll()) {
            event.preventDefault();
            const confirmFix = confirm("Some required fields are missing or invalid.\n\n" +
                "Click OK to review and fix them.\n" +
                "Click Cancel to leave this page.");
            if (confirmFix) {
                alert("Please correct the highlighted fields and submit again.");
            }
            else {
                alert("If you leave now, your changes may be lost.\n" +
                    "Make sure to save your work before exiting.");
            }
        }
    });
});
function commonChartDataExport(wrapper) {
    return {
        charttype: wrapper?.charttype ?? 'line',
        title: wrapper?.chartTitle ?? "Sales Report",
        subtitle: wrapper?.chartSubtitle ?? "Monthly performance",
        xAxisLabel: wrapper?.xAxisLabel ?? "Months",
        yAxisLabel: wrapper?.yAxisLabel ?? "Revenue",
        alignment: wrapper?.chartAlignment ?? "center",
        categories: ["Jan", "Feb", "Mar", "Apr"],
        colors: wrapper?.chartColor ?? ["#3b82f6", "#22c55e"],
        series: [
            {
                name: "Sales",
                data: [10, 20, 30, 40]
            },
            {
                name: "Profit",
                data: [5, 15, 25, 35]
            }
        ]
    };
}
let chartInstance = null;
class SingleTonInstanceHightChart {
    static instance = null;
    static renderChart(highcharts, wrapper) {
        const HC = highcharts ?? window.Highcharts;
        if (!HC) {
            console.warn("Highcharts not available yet.");
            return;
        }
        this.instance = HC;
        const data = commonChartDataExport(wrapper);
        let config = {};
        const type = (data.charttype || "line").toLowerCase();
        // 🔥 ---------------- NORMAL CHARTS ----------------
        if (!["gauge", "funnel", "scatter"].includes(type)) {
            config = {
                chart: {
                    type: type
                },
                title: {
                    text: data.title,
                    align: (data.alignment || "center").toLowerCase()
                },
                subtitle: {
                    text: data.subtitle,
                    align: (data.alignment || "center").toLowerCase()
                },
                xAxis: {
                    title: {
                        text: data.xAxisLabel
                    },
                    categories: ["column", "bar", "line", "area"].includes(type)
                        ? data.categories
                        : undefined
                },
                yAxis: {
                    title: {
                        text: data.yAxisLabel
                    }
                },
                series: data.series
            };
        }
        //  ---------------- SCATTER ----------------
        else if (type === "scatter") {
            config = {
                chart: { type: "scatter" },
                title: {
                    text: data.title,
                    align: (data.alignment || "center").toLowerCase()
                },
                xAxis: { title: { text: data.xAxisLabel } },
                yAxis: { title: { text: data.yAxisLabel } },
                series: [{
                        name: "Scatter",
                        data: [10, 20, 30, 40, 50].map((y, i) => [i, y])
                    }]
            };
        }
        // 🔥 ---------------- FUNNEL ----------------
        else if (type === "funnel") {
            config = {
                chart: { type: "funnel" },
                title: {
                    text: data.title,
                    align: (data.alignment || "center").toLowerCase()
                },
                series: [{
                        name: "Funnel",
                        data: [
                            ["Leads", 100],
                            ["Interested", 80],
                            ["Qualified", 50],
                            ["Converted", 30]
                        ]
                    }]
            };
        }
        // 🔥 ---------------- GAUGE ----------------
        else if (type === "gauge") {
            config = {
                chart: { type: "gauge" },
                title: {
                    text: data.title,
                    align: (data.alignment || "center").toLowerCase()
                },
                pane: {
                    startAngle: -150,
                    endAngle: 150
                },
                yAxis: {
                    min: 0,
                    max: 100,
                    title: {
                        text: data.yAxisLabel || "Value"
                    }
                },
                series: [{
                        name: "Speed",
                        data: [80]
                    }]
            };
        }
        // 🔥 ---------------- CREATE / UPDATE ----------------
        if (!chartInstance) {
            chartInstance = HC.chart("container", config);
        }
        else {
            chartInstance.update(config, true, true);
        }
    }
}
class ChartOperations {
    charttype;
    constructor() {
        this.charttype = "";
    }
    async chartOperations(chart) {
        this.charttype = chart;
        return await this.getAllChartOperation(this.charttype);
    }
    async getAllChartOperation(chartName) {
        console.log(chartName);
        const response = await fetch(`../pagination/chart-data_grid.php?chart_type=${chartName}`);
        const data = await response.json();
        return data;
    }
    renderOptionMenus(element, data = []) {
        element.innerHTML = "";
        const defaultOption = document.createElement("option");
        defaultOption.value = "";
        defaultOption.textContent = "-- Select Operation --";
        element.appendChild(defaultOption);
        if (data.length === 0) {
            const opt = document.createElement("option");
            opt.value = "";
            opt.textContent = "No Data Found";
            element.appendChild(opt);
            return;
        }
        data.forEach((item) => {
            const option = document.createElement("option");
            option.value = item.id;
            option.textContent = item.operation;
            element.appendChild(option);
        });
    }
}
