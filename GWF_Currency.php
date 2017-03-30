<?php
/**
 * @author gizmore
*/
final class GWF_Currency extends GDO
{
	###########
	### GDO ###
	###########
	public function getClassName() { return __CLASS__; }
	public function getTableName() { return GWF_TABLE_PREFIX.'currency'; }
	public function getColumnDefines()
	{
		return array(
			'curr_iso' => array(GDO::PRIMARY_KEY|GDO::TOKEN, GDO::NOT_NULL, 3), // EUR,USD,ETC
			'curr_symbol' => array(GDO::VARCHAR|GDO::UTF8|GDO::CASE_S, GDO::NOT_NULL, 4), // Symbol
			'curr_digits' => array(GDO::TINYINT, GDO::NOT_NULL),
			'curr_ratio' => array(GDO::DECIMAL, GDO::NULL, array(9,9)), // Exchange ratio to EUR
			'curr_automatic' => array(GDO::TINYINT, '1'),
			'curr_synced_at' => array(GDO::TIME, GDO::NULL),
		);
	}

	##############
	### Getter ###
	##############
	public function getSymbol() { return $this->getVar('curr_char'); }
	public function isSyncAutomated() { return $this->getVar('curr_automatic') > 0; }

	################
	### Display ####
	################
	public function displayValue($value, $with_symbol=true) { return sprintf('%s%.0'.$this->getVar('curr_digits').'f', $with_symbol ? $this->getSymbol().'' : '', $value); }

	###############
	### Factory ###
	###############
	/**
	* Return all available ISOs.
	*/
	public static function getISOs()
	{
		return self::table(__CLASS__)->selectColumn('curr_iso');
	}

	/**
	 * @param string $iso
	 * @return GWF_Currency
	 */
	public static function getByISO($iso)
	{
		return self::table(__CLASS__)->selectFirstObject('*', 'curr_iso=\''.self::escape($iso).'\'');
	}

	/**
	 * @param string $iso
	 * @return GWF_Currency
	 */
	public static function blank($iso)
	{
		return new self(array(
			'curr_iso' => $iso,
			'curr_symbol' => $iso,
			'curr_digits' => '2',
			'curr_ratio' => null,
			'curr_automatic' => '1',
			'curr_synced_at' => null,
		));
	}
	
	##################
	### Conversion ###
	##################
	public static function convert($value, $from, $to)
	{

	}
}
