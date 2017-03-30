<?php
/**
 * @author gizmore
 * @version 4.00
 */
final class Module_Currency extends GWF_Module
{
	private static $instance;
	/**
	 * @return Module_Currency
	 */
	public static function instance() { return self::$instance; }

	##################
	### GWF_Module ###
	##################
	public function getVersion() { return 4.00; }
	public function onLoadLanguage() { return $this->loadLanguage('lang/currency'); }
	public function getClasses() { return array('GWF_Currency'); }
	public function onInstall($dropTable) { require_once 'GWF_CurrencyInstall.php'; return GWF_CurrencyInstall::install($this, $dropTable); }
	public function onCronjob() { require_once('GWF_CurrencyCronjob.php'); return GWF_CurrencyCronjob::onCronjob($this); }
	public function getAdminSectionURL() { return $this->getMethodURL('Admin'); }
	public function onStartup()
	{
		self::$instance = $this;
	}

	##############
	### Config ###
	##############
	public function cfgLastTry() { return $this->getModuleVarDate('last_try', '19700101'); }
	public function cfgLastSync() { return $this->getModuleVarDate('last_sync', '19700101'); }
}
