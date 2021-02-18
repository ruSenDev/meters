<table class="data-table meters-table">
		<thead>
		<tr>
		
		//1
			<th rowspan="2"><?= GetMessage("CTM_METER") ?></th>
			<?
			if ($arResult["TEST_PARAM"])
				echo '<th rowspan="2">'.'Заводской номер'.'</th>';
			?>
			<?
			for ($i = 1; $i <= $arResult['MAX_VALUES']; $i++)
			{
				?>
				<th colspan="2" class="cost-head-top"><?= str_replace("#N#", $i, GetMessage("CTM_TARIFF_N")) ?></th>
			<?
			}
			?>
			<th rowspan="2"><?= GetMessage("CTM_VERIFICATION_DATE") ?></th>
			<th rowspan="2"><?= GetMessage("CTM_METER_HISTORY") ?></th>
		</tr>
		<tr>
		// 2
			<?
			for ($i = 1; $i <= $arResult['MAX_VALUES']; $i++)
			{
				?>
				<th class="cost-head">
					<small style="font-weight: normal;"
					       title="<?= GetMessage("CTM_PREV_VALUE_TITLE") ?>"><?= GetMessage("CTM_PREV_VALUE") ?></small>
				</th>
				<th class="cost-head">
					<small style="font-weight: normal;"
					       title="<?= GetMessage("CTM_CURRENT_VALUE_TITLE") ?>"><?= GetMessage("CTM_CURRENT_VALUE") ?></small>
				</th>
			<?
			}
			?>
		</tr>
		</thead>
		<tbody>
		<?
		if (empty($arResult['ITEMS']))
		{
			?>
			<tr>
			<td colspan="<?= (3 + $arResult['MAX_VALUES'] * 2) ?>"><em><?= GetMessage("CTM_YOU_HAVE_NO_METERS") ?></em>
			</td></tr><?
		}
		else
		{
			//3
			foreach ($arResult['ITEMS'] as $id => $arItem)
			{
				?>
				<tr>
					<td class="meter-name"><?= strlen($arItem['SERVICE_NAME']) > 0 ? $arItem['SERVICE_NAME'] : $arItem['NAME'] ?>
						:
					</td>
					<?
					if ($arResult["TEST_PARAM"])
					echo '<td >'.$arItem['NUM'].'</td>';
					?>
					<?
					for ($i = 1; $i <= $arItem["VALUES_COUNT"]; $i++)
					{
						?>
						<td class="cost">
							<?= FloatVal($arItem['PREV_VALUE']['VALUE' . $i]) ?>
						</td>
						<td class="cost">
							<? if ($arResult['allow_edit']): ?>
								<input type="text" name="indiccur<?= $i ?>[<?= $arItem['ID'] ?>]"
								       value="<?= $arItem['VALUE']['VALUE' . $i] ?>" style="width: 50px;"
								       class="styled">
							<? else: ?>
								<?= $arItem['VALUE']['VALUE' . $i] ?>
							<?endif ?>
						</td>
					<?
					}

					if ($arResult["MAX_VALUES"] > $arItem["VALUES_COUNT"])
					{
						?>
						<td colspan="<?= (($arResult["MAX_VALUES"] - $arItem["VALUES_COUNT"]) * 2) ?>">&nbsp;</td>
					<?
					}
					?>
					<td class="center"><?= $arItem["VERIFICATION_DATE"] ?></td>
					<td class="center meter-history">
						<a href="<?= $arResult["HISTORY_URL"] ?>&id=<?= $arItem["ID"]?>"><?= GetMessage("CTM_METER_HISTORY") ?></a>
					</td>
				</tr>
			<?
			}
		}
		?>
		</tbody>
		<?
		if ($arResult['allow_edit'])
		{
			?>
			<tfoot>
			<tr>
				<td colspan="<?= (3 + $arResult['MAX_VALUES'] * 2) ?>" style="text-align: right; padding-right: 30px;">
					<input type="submit" name="submit_btn" value="<?= GetMessage("CTM_BTN_CAPTION") ?>"/>
				</td>
			</tr>
			</tfoot>
		<?
		}
		?>
	</table>