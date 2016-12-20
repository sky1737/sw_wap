/**
 * main3.js
 * http://www.codrops.com
 *
 * Licensed under the MIT license.
 * http://www.opensource.org/licenses/mit-license.php
 *
 * Copyright 2014, Codrops
 * http://www.codrops.com
 */
(function () {
    var chartModel = intiChartModel();
    var saleChart = initSaleChart(chartModel);
    initDdlistBtn(saleChart, chartModel);
    var bodyEl = document.body,
        content = document.querySelector('.content-wrap'),
        openbtn = document.getElementById('open-button'),
        closebtn = document.getElementById('close-button'),
        isOpen = false,
        morphEl = document.getElementById('morph-shape'),
        s = Snap(morphEl.querySelector('svg'));
    path = s.select('path');
    initialPath = this.path.attr('d'),
        pathOpen = morphEl.getAttribute('data-morph-open'),
        isAnimating = false;
    function init() {
        initEvents();
    }

    function initEvents() {
        openbtn.addEventListener('click', toggleMenu);
        if (closebtn) {
            closebtn.addEventListener('click', toggleMenu);
        }

        // close the menu element if the target it´s not the menu element or one of its descendants..
        content.addEventListener('click', function (ev) {
            var target = ev.target;
            if (isOpen && target !== openbtn) {
                toggleMenu();
            }
        });
    }

    function toggleMenu() {
        if (isAnimating) return false;
        isAnimating = true;
        if (isOpen) {
            classie.remove(bodyEl, 'show-menu');
            // animate path
            setTimeout(function () {
                // reset path
                path.attr('d', initialPath);
                isAnimating = false;
            }, 300);
        }
        else {
            classie.add(bodyEl, 'show-menu');
            // animate path
            path.animate({'path': pathOpen}, 400, mina.easeinout, function () {
                isAnimating = false;
            });
        }
        isOpen = !isOpen;
    }

    function initDdlistBtn(saleChart, model) {
        var btn = document.getElementById('ddlist_btn');
        var select = document.getElementById('ddlist_select');
        var saleTotal = document.getElementById('saleTotal');
        saleTotal.innerHTML = 0;
        btn.onclick = function () {
            var val = ddlist_select.value;
            saleChart.data.labels = model[val]['x'];
            saleChart.data.datasets[0].data = model[val]['y'];
            saleTotal.innerHTML = model[val]['total'];
            saleChart.update();
        };

    };
    function initSaleChart(model) {
        var ctx = document.getElementById("saleChart").getContext("2d");
        var data = {
            labels: [],
            datasets: [
                {
                    label: "销售额",
                    fill: false,
                    lineTension: 0.1,
                    backgroundColor: "rgba(75,192,192,0.4)",
                    borderColor: "rgba(75,192,192,1)",
                    borderCapStyle: 'butt',
                    borderDash: [],
                    borderDashOffset: 0.0,
                    borderJoinStyle: 'miter',
                    pointBorderColor: "rgba(75,192,192,1)",
                    pointBackgroundColor: "#fff",
                    pointBorderWidth: 1,
                    pointHoverRadius: 5,
                    pointHoverBackgroundColor: "rgba(75,192,192,1)",
                    pointHoverBorderColor: "rgba(220,220,220,1)",
                    pointHoverBorderWidth: 2,
                    pointRadius: 1,
                    pointHitRadius: 10,
                    data: [],
                    spanGaps: false,
                }
            ]
        };
        var options = {
            title: {
                display: true,
                text: ''
            }
        };
        saleChart = new Chart(ctx, {
            type: 'line',
            data: data,
            options: options
        });
        return saleChart;
    };
    init();
})();