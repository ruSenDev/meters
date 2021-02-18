<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
use Bitrix\Main\Page\Asset;

Asset::getInstance()->addJs($componentPath . "/templates/metter_rev/chart/core.js");
Asset::getInstance()->addJs($componentPath . "/templates/metter_rev/chart/charts.js");
Asset::getInstance()->addJs($componentPath . "/templates/metter_rev/chart/animated.js");
Asset::getInstance()->addJs($componentPath . "/templates/metter_rev/chart/ru_RU.js");

if (empty($arResult['METERS'])) :
    ShowNote(GetMessage("CITRUS_TSZH_METER_VALUES_NOT_FOUND"));
else :
    ?>

    <div class="met-history">
        <div class="met-history__title">
            <p><span class="bold"><?= GetMessage("CITRUS_TSZH_METERS_NAME") ?></span> <?= $arResult["METERS"]["NAME"] ?>
            </p>
            <p><span class="bold"><?= GetMessage("CITRUS_TSZH_METERS_NUM") ?></span> <?= $arResult["METERS"]["NUM"] ?>
            </p>
            <p>
                <span class="bold"><?= GetMessage("CITRUS_TSZH_METERS_SERVICE_NAME") ?></span> <?= $arResult["METERS"]["SERVICE_NAME"] ?>
            </p>
        </div>
        <div class="met-history__block">
            <?php
            $chart1_vals = '';
            $chart1_captions = '';
            foreach ($arResult['tariffs'] as $met_periods) {
                ?>
                <table class="table1">
                    <?php if (isset($met_periods['name'])) { ?>
                        <thead>
                        <tr>
                            <td colspan="3"><?= $met_periods['name'] ?></td>
                        </tr>
                        </thead>
                    <?php } ?>
                    <tbody>
                    <tr>
                        <td><?= GetMessage("CITRUS_TSZH_METERS_DATE_INPUT") ?></td>
                        <td><?= GetMessage("CITRUS_TSZH_METERS_VALUE") ?></td>
                        <td><?= GetMessage("CITRUS_TSZH_CONSUMPTION") ?></td>
                    </tr>
                    <?php
                    foreach ($met_periods['periods'] as $met_period) {
                        ?>
                        <tr>
                            <td>
                                <?= $arResult["ROWS"][$met_period['date']]["INPUT_DATE"] ?>
                            </td>
                            <td>
                                <?= (float)$met_period['data'] ?>
                            </td>
                            <td>
                                <?= (float)$met_period['rate'] ?>
                            </td>
                        </tr>
                        <?php
                    }

                    if ($arResult["FLAG_NAV"]) {
                        ?>
                        <tr>
                            <td colspan="3">
                                <?
                                $APPLICATION->IncludeComponent(
                                    "bitrix:main.pagenavigation",
                                    "",
                                    array(
                                        "NAV_OBJECT" => $arResult["NAV"],
                                        "SEF_MODE" => "N",
                                        "SHOW_COUNT" => "Y",
                                        "COMPONENT_TEMPLATE" => ".default",
                                    ),
                                    false
                                );
                                ?>
                            </td>
                        </tr>
                        <?
                    }
                    if (count($met_periods['periods']) == 0) {
                        ?>
                        <tr>
                            <td colspan="3">
                                <?= GetMessage("CITRUS_TSZH_NO_DATA"); ?>
                            </td>
                        </tr>
                        <?
                    }
                    ?>
                    </tbody>
                </table>
                <?php
            }
            ?>
            <div class="chart1-xy"></div>
            <div id="chartdiv"></div>

        </div>
    </div>
<?php endif; ?>

<?
foreach ($arResult['tariffs'] as $key => $metas_periods) {
    $jP[$key] = $metas_periods;
}

for ($i = 1; $i <= count($jP); $i++) {

}

foreach ($jP[1] as $periods) {
    $jPeriod1 = json_encode($periods);
}

foreach ($jP[2] as $periods) {
    $jPeriod2 = json_encode($periods);
}

?>

<script>
    $(document).ready(function () {
        new_href = <?echo \Bitrix\Main\Web\Json::encode($arResult["BACK_URL"], $options = null);?>;
        $(this).find('.content__page').find('.breadcrumbs').find('.breadcrumbs__link').attr("href", new_href);
    });

    $(document).ready(function () {

        var data1 = JSON.parse(<?php echo json_encode($jPeriod1); ?>);
        var data2 = JSON.parse(<?php echo json_encode($jPeriod2); ?>);

        var arrObjects1 = [];
        for (var i1 in data1) {
            arrObjects1.push(data1[i1]);
        }

        var arrObjects2 = [];
        for (var i2 in data2) {
            arrObjects2.push(data2[i2]);
        }

        am4core.useTheme(am4themes_animated);

// Create chart instance
        var chart = am4core.create("chartdiv", am4charts.XYChart);
        chart.language.locale = am4lang_ru_RU;

        var title = chart.titles.create();
        title.text = "<?= $arResult["METERS"]["SERVICE_NAME"] ?>";
        title.fontSize = 25;
        title.marginBottom = 30;

// Add data
        chart.data = arrObjects1;

// Create axes
        var categoryAxis = chart.xAxes.push(new am4charts.DateAxis());
        categoryAxis.renderer.grid.template.location = 0;
        categoryAxis.title.text = "<?= GetMessage("CITRUS_TSZH_PERIOD") ?>";
        categoryAxis.renderer.minGridDistance = 30;

        var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
        valueAxis.title.text = "<?= GetMessage("CITRUS_TSZH_CONSUMPTION") ?>";

// Create series
        function createSeries(field, name) {
            var series = chart.series.push(new am4charts.LineSeries());
            series.dataFields.valueY = 'data';
            series.dataFields.dateX = "date";
            series.name = name;
            series.tooltipText = "{dateX}: [b]{valueY}[/]";
            series.strokeWidth = 2;

            var bullet = series.bullets.push(new am4charts.CircleBullet());
            bullet.circle.stroke = am4core.color("#fff");
            bullet.circle.strokeWidth = 2;
        }

        createSeries("value", "<?= $jP[1]['name'] ?>");

        chart.legend = new am4charts.Legend();
        chart.cursor = new am4charts.XYCursor();
    });

</script>