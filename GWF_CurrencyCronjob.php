<?php
final class GWF_CurrencyCronjob extends GWF_Cronjob
{
	public static function onCronjob(Module_Currency $module)
	{
		self::start($module->getName());
		self::trySyncCurrencies($module);
		self::end($module->getName());
	}
	
	public static function trySyncCurrencies(Module_Currency $module)
	{
		$lastTry = $module->cfgLastTry();
		$nowTry = GWF_Time::getDate(GWF_Date::LEN_HOUR);
		if ($lastTry !== $nowTry)
		{
			$module->saveModuleVar('last_try', $nowTry);
			self::syncCurrencies($module);
		}
	}

	public static function syncCurrencies(Module_Currency $module)
	{
		self::notice("Requesting EZB exchange rates");
		$xml = simplexml_load_file("http://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml");
		$nowStamp = str_replace('-', '', $xml->Cube->Cube["time"]);
		$lastStamp = $module->cfgLastSync();
		if ($nowStamp !== $lastStamp)
		{
			self::notice("Got new EZB exchange rates");
			foreach($xml->Cube->Cube->Cube as $rate)
			{
				self::syncCurrency($module, $rate['currency'], $rate['rate']);
			}
			$module->saveModuleVar('last_sync', $nowStamp);
		}
	}
	
	private static function syncCurrency(Module_Currency $module, $iso, $rate)
	{
		if (!($currency = GWF_Currency::getByISO($iso)))
		{
			$currency = GWF_Currency::blank($iso);
		}
		
		if ($currency->isSyncAutomated())
		{
			$currency->setVar('curr_ratio', $rate);
			$currency->setVar('curr_synced_at', time());
			$currency->replace();
		}
	}
	
}
