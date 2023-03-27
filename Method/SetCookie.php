<?php
namespace GDO\CORS\Method;

use GDO\Core\Application;
use GDO\Core\GDT;
use GDO\Core\Method;

/**
 * Cookie setting triggered by a web-bug.
 *
 * @version 7.0.1
 * @since 6.11.3
 * @author gizmore
 * @deprecated Still needed?
 */
final class SetCookie extends Method
{

	public function execute(): GDT
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
			echo $picture;
		}
		return Application::exit();
	}

}
