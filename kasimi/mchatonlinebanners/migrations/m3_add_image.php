<?php

/**
 *
 * mChat Online Banners. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2018 kasimi - https://kasimi.net
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace kasimi\mchatonlinebanners\migrations;

use phpbb\config\db_text;
use phpbb\db\migration\container_aware_migration;

class m3_add_image extends container_aware_migration
{
	/**
	 * @return array
	 */
	public static function depends_on()
	{
		return ['\kasimi\mchatonlinebanners\migrations\m2_onlinebanners'];
	}

	/**
	 * @return array
	 */
	public function update_data()
	{
		return [
			['custom', [[$this, 'add_image']]],
		];
	}

	/**
	 *
	 */
	public function add_image()
	{
		/** @var db_text $config_text */
		$config_text = $this->container->get('config_text');

		$json = $config_text->get('mchat_online_banners');

		$banners = @json_decode($json, true);

		if (is_array($banners))
		{
			foreach ($banners as &$banner)
			{
				$banner['image'] = '';
			}
		}
		else
		{
			$banners = [];
		}

		$config_text->set('mchat_online_banners', json_encode($banners));
	}
}
