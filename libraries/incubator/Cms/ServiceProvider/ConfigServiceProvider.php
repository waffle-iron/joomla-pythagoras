<?php
/**
 *
 * @copyright  Copyright (C) 2015 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Cms\ServiceProvider;

use Dotenv\Dotenv;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use Joomla\Registry\Registry;

/**
 * Load the Configuration Data.
 *
 * The configuration is read from a file in the directory passed to the
 * constructor (defaults to `.env`).
 *
 * @since 1.0
 */
class ConfigServiceProvider implements ServiceProviderInterface
{
	/**
	 * Add the configuration from the environment to a container
	 *
	 * @param   Container $container The container
	 * @param   string    $alias     An optional alias, defaults to 'config'
	 *
	 * @return  void
	 */
	public function register(Container $container, $alias = 'config')
	{
		$file = '.env';

		if ($container->has('ConfigFileName'))
		{
			$file = $container->get('ConfigFileName');
		}

		$dotenv = new Dotenv($container->get('ConfigDirectory'), $file);
		$dotenv->overload();

		$container->set($alias, new Registry($_ENV), true);
	}
}
