<?php
namespace GDO\CORS;

use GDO\Core\GDO_Module;
use GDO\Core\GDT_Checkbox;
use GDO\Util\Common;

/**
 * Add CORS headers on non cli requests.
 * Optional: Allow any origin. will try a lot of possible working values from request. If cors is set in request vars you can force it.
 * Default to SERVER_NAME.
 *
 * @version 6.11.3
 * @since 6.7.0
 * @author gizmore
 */
final class Module_CORS extends GDO_Module
{

	##############
	### Module ###
	##############
	public int $priority = 10;

	public function onLoadLanguage(): void { $this->loadLanguage('lang/cors'); }

	##############
	### Config ###
	##############
	public function getConfig(): array
	{
		return [
			GDT_Checkbox::make('cors_allow_any')->initial('0'),
			GDT_Checkbox::make('cors_allow_creds')->initial('1'),
		];
	}

	public function onIncludeScripts(): void
	{
		$this->addJS('js/gdo6-cors.js');
	}

	public function onModuleInit()
	{
// 		if (@$_SERVER['REQUEST_METHOD'] === 'OPTIONS')
// 		{
		# Origin
		hdr('Access-Control-Allow-Origin: ' . $this->getOrigin());

		# Credentials
		if ($this->cfgAllowCredentials())
		{
			hdr('Access-Control-Allow-Credentials: true');
		}

		# Options
		hdr('Access-Control-Allow-Headers: Content-Type, Authorization, Cookie, Accept, x-csrf-token');
		hdr('Access-Control-Allow-Methods: GET, POST, OPTIONS');
		hdr('Access-Control-Expose-Headers: Set-Cookie');
// 		}
	}

	############
	### Init ###
	############

	private function getOrigin()
	{
		if ($this->cfgAllowAny())
		{
			if ($cors = Common::getRequestString('_cors'))
			{
				unset($_REQUEST['_cors']);
				return $cors;
			}
			if ($cors = @$_SERVER['HTTP_ORIGIN'])
			{
				return $cors;
			}
			if ($cors = @$_SERVER['HTTP_REFERER'])
			{
				return $cors;
			}
			if ($cors = @$_SERVER['REMOTE_ADDR'])
			{
				return $cors;
			}
		}
		return @$_SERVER['SERVER_NAME'];
	}

	public function cfgAllowAny() { return $this->getConfigValue('cors_allow_any'); }

	public function cfgAllowCredentials() { return $this->getConfigValue('cors_allow_creds'); }

}
