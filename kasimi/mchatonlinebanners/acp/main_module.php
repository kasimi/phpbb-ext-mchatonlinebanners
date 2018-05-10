<?php

/**
 *
 * mChat Online Banners. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2018 kasimi - https://kasimi.net
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace kasimi\mchatonlinebanners\acp;

class main_module extends base
{
	/**
	 * @return string
	 */
	protected function get_controller_service_id()
	{
		return 'kasimi.mchatonlinebanners.controller.acp';
	}
}
