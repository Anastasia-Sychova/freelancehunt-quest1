let params = document.getElementById('init_params').dataset;
let pieData = JSON.parse(params.pie_data);
let pieChart = document.getElementById("pieChart");
let pieChartLegend = document.getElementById("pieChartLegend");
pieChart.width = 300;
pieChart.height = 300;

function drawPieSlice(context,centerX, centerY, radius, startAngle, endAngle, color) {
    context.fillStyle = color;
    context.beginPath();
    context.moveTo(centerX,centerY);
    context.arc(centerX, centerY, radius, startAngle, endAngle);
    context.closePath();
    context.fill();
}

let PieChart = function(options) {
    this.options = options;
    this.canvas = options.canvas;
    this.context = this.canvas.getContext("2d");
    this.colors = options.colors;

    this.draw = function() {
        let total_value = 0;
        let color_index = 0;
        for (let item in this.options.data) {
            let val = this.options.data[item];
            total_value += val;
        }

        let startAngle = 0;
        for (let item in this.options.data){
            let value = this.options.data[item];
            let sliceAngle = 2 * Math.PI * value / total_value;

            drawPieSlice(
                this.context,
                this.canvas.width / 2,
                this.canvas.height / 2,
                Math.min(this.canvas.width / 2,this.canvas.height / 2),
                startAngle,
                startAngle + sliceAngle,
                this.colors[ color_index % this.colors.length]
            );

            startAngle += sliceAngle;
            color_index++;
        }

        startAngle = 0;
        for (let item in this.options.data) {
            let value = this.options.data[item];
            let sliceAngle = 2 * Math.PI * value / total_value;
            let pieRadius = Math.min(this.canvas.width/2, this.canvas.height/2);
            let labelX = this.canvas.width/2 + (pieRadius / 2) * Math.cos(startAngle + sliceAngle/2);
            let labelY = this.canvas.height/2 + (pieRadius / 2) * Math.sin(startAngle + sliceAngle/2);

            let labelText = Math.round(100 * value / total_value);
            this.context.fillStyle = "white";
            this.context.font = "bold 15px Arial";
            this.context.fillText(labelText+"%", labelX,labelY);
            startAngle += sliceAngle;
        }

        if (this.options.legend) {
            color_index = 0;
            let legendHTML = "";
            for (item in this.options.data) {
                legendHTML += "<div class='legendItem'><span class='legendBlock' style='background-color:"+this.colors[color_index++]+";'>&nbsp;</span> "+item+"</div>";
            }
            this.options.legend.innerHTML = legendHTML;
        }
    }
}

let myPieChart = new PieChart(
    {
        canvas: pieChart,
        data: pieData,
        colors: ["#f55702", "#007b9e", "#907d02", "#82224c"],
        legend: pieChartLegend,
    }
);
myPieChart.draw();
