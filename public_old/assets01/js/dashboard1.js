// Morris bar chart
Morris.Bar({
    element: 'morris-bar-chart',
    data: [{
        y: 'Jan 22',
        visit: 100,
        down: 90,
    }, {
        y: 'Feb 22',
        visit: 75,
        down: 65,
    } ],
    xkey: 'y',
    ykeys: ['visit', 'down'],
    labels: ['Berkunjung', 'Unduh',],
    barColors: ['#012241', '#00a5e5', '#00c292'],
    hideHover: 'auto',
    barSizeRatio: 0.45,
    gridLineColor: '#eef0f2',
    resize: true
});

// This is for the sparkline chart

var sparklineLogin = function() {

    $('#sparkline2dash').sparkline([6, 10, 9, 11, 9, 10, 12], {
        type: 'bar',
        height: '154',
        barWidth: '4',
        resize: true,
        barSpacing: '10',
        barColor: '#25a6f7'
    });
    $('#sales1').sparkline([6, 10, 9, 11, 9, 10, 12], {
        type: 'bar',
        height: '154',
        barWidth: '4',
        resize: true,
        barSpacing: '10',
        barColor: '#fff'
    });

}
var sparkResize;

$(window).resize(function(e) {
    clearTimeout(sparkResize);
    sparkResize = setTimeout(sparklineLogin, 500);
});
sparklineLogin();
