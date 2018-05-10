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

class main_info
{
	/**
	 * @return array
	 */
	public function module()
	{
		return [
			'filename'	=> '\kasimi\mchatonlinebanners\acp\main_module',
			'title'		=> 'ACP_MCHATONLINEBANNERS_TITLE',
			'modes'		=> [
				'onlinebanners' => [
					'title'		=> 'ACP_MCHATONLINEBANNERS_TITLE',
					'auth'		=> 'ext_kasimi/mchatonlinebanners && acl_a_mchat',
					'cat'		=> ['ACP_MCHATONLINEBANNERS_TITLE'],
				],
			],
		];
	}
}
