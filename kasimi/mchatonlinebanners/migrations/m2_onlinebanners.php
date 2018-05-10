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

use kasimi\mchatonlinebanners\includes\helper;

class m2_onlinebanners extends \phpbb\db\migration\migration
{
	/**
	 * @return array
	 */
	public static function depends_on()
	{
		return ['\kasimi\mchatonlinebanners\migrations\m1_acp_module'];
	}

	/**
	 * @return array
	 */
	public function update_data()
	{
		return [
			['config_text.add', ['mchat_online_banners', helper::get_empty_banners()]],
		];
	}
}
