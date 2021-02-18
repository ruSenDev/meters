<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
foreach ($arResult['ITEMS'] as $key => $arItem) {
    if ($arItem['SERVICE_ID'] > 0) {
        //получаем единицы измерения
        $service = CTszhService::GetByID($arItem['SERVICE_ID']);
        $arResult["ITEMS"][$key]["UNIT"] = $service["UNITS"];
    }

    for ($i = 1; $i <= $arItem['VALUES_COUNT']; $i++) {
        if (isset($_POST["sessid"])) {
//            foreach ($arResult['ITEMS'] as $id => $arItem) {
                if ($arItem['VALUE']['VALUE' . $i] != null) {
                    $arResult['ITEMS'][$key]['VALUE']['UP_DATE'] = date("d.m.Y");
                }
//            }
        }
    }

}





