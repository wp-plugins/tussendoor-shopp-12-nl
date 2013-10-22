<?php
/*
Plugin Name: Shopp (nl)
Plugin URI: http://tussendoor.nl/
Description: Extends the Shopp plugin and add-ons with the Dutch language: <strong>Shopp</strong> 1.2
Version: 1.3
Requires at least: 3.0
Author: Tussendoor
Author URI: http://tussendoor.nl/
License: GPL
Text Domain: shopp_nl
*/

if(file_exists('Shopp.php'))
{
	require_once 'Shopp.php';
}

class ShoppNL 
{
	/**
	 * The current langauge
	 *
	 * @var string
	 */
	private static $language;

	/**
	 * Flag for the dutch langauge, true if current langauge is dutch, false otherwise
	 *
	 * @var boolean
	 */
	private static $isDutch;

	////////////////////////////////////////////////////////////

	/**
	 * Bootstrap
	 */
	public static function bootstrap() 
	{
		add_action('init', array(__CLASS__, 'init'), 8);
		
		add_filter('load_textdomain_mofile', array(__CLASS__, 'loadMoFile'), 10, 2);
	}
	
	////////////////////////////////////////////////////////////

	/**
	 * Initialize
	 */
	public static function init() 
	{		
		// Constants
		self::$language = get_option('WPLANG', WPLANG);
		self::$isDutch = (self::$language == 'nl' || self::$language == 'nl_NL');
		
		if(defined('ICL_LANGUAGE_CODE')) 
		{
			self::$isDutch = ICL_LANGUAGE_CODE == 'nl';
		}
		$relPath = dirname(plugin_basename(__FILE__)) . '/languages/';
		
		load_plugin_textdomain('Shopp', false, $relPath);
	}
	
	////////////////////////////////////////////////////////////

	/**
	 * Load text domain MO file
	 *
	 * @param string $moFile
	 * @param string $domain
	 */
	public static function loadMoFile($moFile, $domain) 
	{
		if(self::$language == null) 
		{
			self::$language = get_option('WPLANG', WPLANG);
			self::$isDutch = (self::$language == 'nl' || self::$language == 'nl_NL');

			if(defined('ICL_LANGUAGE_CODE')) 
			{
				self::$isDutch = ICL_LANGUAGE_CODE == 'nl';
			}
		}
		
		$isShopp = ($domain == 'Shopp');
		if(self::$isDutch && $isShopp) 
		{
			$version = null;
			
			if(class_exists('Shopp')) 
			{
				$version = SHOPP_VERSION;
			}
			$moFile = self::getMoFile('shopp', $version);
		}
		
		$newMofile = null;

		$isShoppDomain = ($domain == 'Shopp');
		
		if($isShoppDomain) 
		{
			
			$isShopp = strpos($moFile, '/shopp/') !== false;
		}
		if($isShopp) 
		{	
			$version = SHOPP_VERSION;
			if(strpos($moFile, '/shopp/languages/shopp-') !== false) 
			{
				$newMofile = self::getMoFile('shopp', $version);
			} 
			
		}
		if(is_readable($newMofile)) {
			$moFile = $newMofile;
		}
		return $moFile;	
	}

	////////////////////////////////////////////////////////////

	/**
	 * Get the MO file for the specified domain, version and language
	 */
	public static function getMoFile($domain, $version) {
		$dir = dirname(__FILE__);
		
		$moFile = $dir . '/languages/' . $domain . '/' . $version . '/' . self::$language . '.mo';

		// if specific version MO file is not available point to the current public release (cpr) version 
		if(!is_readable($moFile)) {
			$moFile = $dir . '/languages/' . $domain . '/cpr/' . self::$language . '.mo';
		}

		return $moFile;
	}
}

ShoppNL::bootstrap();
