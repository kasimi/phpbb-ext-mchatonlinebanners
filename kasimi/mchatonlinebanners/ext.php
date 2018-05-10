<?php

/**
 *
 * mChat Online Banners. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2018 kasimi - https://kasimi.net
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace kasimi\mchatonlinebanners;

use phpbb\extension\base;

class ext extends base
{
	/**
	 * @return bool
	 */
	public function is_enableable()
	{
		return phpbb_version_compare(PHPBB_VERSION, '3.2.0', '>=') && phpbb_version_compare(PHP_VERSION, '5.4.7', '>=');
	}
}
