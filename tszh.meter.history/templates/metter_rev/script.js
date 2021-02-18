$(document).ready(function() {
    //отрисовка графиков
    $('.chart1-xy').each(function () {
        var chart1xy = $(this);
        var scaleY = 1;
        var html = '', xlabel = chart1xy.attr('x-label'), ylabel = chart1xy.attr('y-label'),
            valuesPol = chart1xy.attr('values-polyline'), strokeWidth = chart1xy.attr('stroke-width'),
            colors = chart1xy.attr('colors'), ratioYX = chart1xy.attr('ratio-yx'), captions = chart1xy.attr('captions');
       // strokeWidth = strokeWidth;
        if (xlabel)
            xlabel = '<div class="chart1-xy__label-x">' + xlabel + '</div>';
        else
            xlabel = '';
        if (ylabel)
            ylabel = '<div class="chart1-xy__label-y">' + ylabel + '</div>';
        else
            ylabel = '';
        if (colors) {
            colors = colors.split(';');
        }
        else {
            colors = ['red', 'green', 'blue', 'orange'];
        }
        if (!strokeWidth)
            strokeWidth = 1;
        if (!ratioYX)
            ratioYX = 1 / 3;
        if (valuesPol) {
            var charts = valuesPol.split('|');
            var maxX = 0, maxY = 0;
            var polylines = [];
            charts.forEach(function (items, i) {
                var polyline = {color: null, points: []};
                polyline.color = colors[i];
                var item = items.split(';');
                item.forEach(function (itm) {
                    var xy = itm.split(',');
                    polyline.points.push(xy);

                    if (xy.length == 2) {
                        maxX = parseInt(xy[0]) > maxX ? parseInt(xy[0]) : maxX;
                        maxY = parseInt(xy[1]) > maxY ? parseInt(xy[1]) : maxY;
                    }
                });
                polylines.push(polyline);
            });
            if(maxX == 0)
                maxX = 100;
            if(maxY == 0)
                maxY = 100;
            strokeWidth = strokeWidth * maxX/100;
            var axisWidth = 0.3 * maxX/100;
            var lines = '', polylineHtml = '';
            scaleY = ratioYX / (maxY / maxX);
            console.log(maxX);
            var lip = 0;
            var heightY = (scaleY * maxY);
            captions = captions.split(';');
            var captionsHtml = '';
            polylines.forEach(function (item, i) {
                polylineHtml += '<polyline fill="none" stroke="' + item.color + '" stroke-width="' + strokeWidth + '" points="';
                if (captions[i] != '')
                    captionsHtml += '<hr width="20" size="' + strokeWidth + '" color="' + item.color + '" />' + captions[i];
                item.points.forEach(function (itm) {
                    polylineHtml += (parseInt(itm[0]) + lip) + ',' + (heightY - parseInt(itm[1]) * scaleY) + ' ';
                });
                polylineHtml += '"/>';

            });
            //строим оси
            lines += '<line stroke-width="' + axisWidth + '" x1="0" y1="' + heightY + '" x2="' + (maxX + lip) + '" y2="' + heightY + '" class="chart1-xy__axis" />';
            lines += '<line stroke-width="' + axisWidth + '" x1="' + lip + '" y1="0" x2="' + lip + '" y2="' + (heightY + lip) + '" class="chart1-xy__axis" />';
            html += ylabel + '<div class="chart1-xy__charts">' + xlabel + '<svg width="100%" viewBox="0 0 ' + (maxX + lip) + ' ' + (heightY + lip) + '">' + polylineHtml + lines + '</svg></div><div class="chart1-xy__captions">' + captionsHtml + '</div>';
            chart1xy.html(html);
            var svg = chart1xy.find('svg');

            function svgHeight() {
                svg.height(svg.width() * ratioYX);
            }

            $(document).ready(function () {
                jQuery(window).resize(function () {
                    svgHeight();
                });
                if (svg.width() == 100)
                    window.setTimeout(svgHeight, 200);
                if (captionsHtml == '') {
                    chart1xy.children('.chart1-xy__captions').hide();
                }
                svgHeight();
            });
        }
    });
});