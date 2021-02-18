<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$result = array();
$chart1_vals = '';
$chart1_captions = '';
for ($i = 1; $i <= $arResult["MAX_VALUES_COUNT"]; $i++){
    /*$lastdate = */$lastdata = 0;
    $chart1_vals0 = '';
    if($arResult["MAX_VALUES_COUNT"] > 1){
        $result[$i]['name'] = GetMessage("CITRUS_TSZH_METER_TARIF_".$i);
        $chart1_captions .= ';'.$result[$i]['name'];
    }
    $days = 0;
    $item = 0;
    $periods = array();
    ksort($arResult["ROWS"]);
    foreach ($arResult["ROWS"] as $date => $arItem) {
        if($item == 0)
            $lastdata = $arItem["VALUE" . $i];
        $periods[$item]['date'] = $date;
        $periods[$item]['data'] = $arItem["VALUE" . $i] > 0 ? $arItem["VALUE" . $i] : "";
        $periods[$item]['rate'] = $periods[$item]['data'] != "" ? $periods[$item]['data'] - $lastdata : 0;
        //$days += $lastdate === 0 ? '0' : ceil((strtotime($periods[$item]['date'])-$lastdate)/86400);
        $chart1_vals0 .= ';'./*$days*/$item.','.$periods[$item]['rate']*10;
        $lastdata = $periods[$item]['data'];
        //$lastdate = strtotime($periods[$item]['date']);
        $item++;
    }
    krsort($periods);
    $result[$i]['periods'] = $periods;
    $chart1_vals .= '|'. substr($chart1_vals0, 1);
}
if($chart1_captions != '')
    $chart1_captions = substr($chart1_captions, 1);
$arResult["chart1_vals"] = substr($chart1_vals, 1);
$arResult["chart1_captions"] = $chart1_captions;
$arResult["tariffs"] = $result;
?>
