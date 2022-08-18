<?php
namespace GDO\CORS\Method;

use GDO\Core\Method;
use GDO\Core\Application;

/**
 * Cookie setting triggered by a web-bug.
 * 
 * @author gizmore
 * @version 7.0.1
 * @since 6.11.3
 * @deprecated Still needed?
 */
final class SetCookie extends Method
{
	public function execute()
	{
		$path = $this->getModule()->filePath('img/pixel.png');
		$picture = file_get_contents($path);
		hdr('Content-Type: image/png');
		hdr('Content-Size: ' . strlen($picture));
		$app = Application::$INSTANCE;
		$app->timingHeader();

		# out
		if (!$app->isUnitTests())
		{
			die($picture);
		}
	}
	
}
