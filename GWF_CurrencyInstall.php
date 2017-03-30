<?php
final class GWF_CurrencyInstall
{
	public static function install(Module_Currency $module, $dropTables)
	{
		return GWF_ModuleLoader::installVars($module, array(
			'last_try' => array('19700101', 'script', GWF_Date::LEN_HOUR),
			'last_sync' => array('19700101', 'script', GWF_Date::LEN_DAY),
		)).
		$module->onCronjob();
	}
	

}
