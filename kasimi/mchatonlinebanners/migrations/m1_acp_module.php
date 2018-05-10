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

use phpbb\db\migration\container_aware_migration;
use phpbb\db\migration\exception;
use phpbb\db\migration\tool\module;

class m1_acp_module extends container_aware_migration
{
	/**
	 * @return array
	 */
	public static function depends_on()
	{
		return ['\phpbb\db\migration\data\v320\v320'];
	}

	/**
	 * @return array
	 */
	public function update_data()
	{
		return [
			['custom', [[$this, 'add_mchat_category']]],

			['module.add', [
				'acp',
				'ACP_CAT_MCHAT',
				[
					'module_basename'	=> '\kasimi\mchatonlinebanners\acp\main_module',
					'modes'				=> ['onlinebanners'],
				],
			]],
		];
	}

	/**
	 * @throws exception
	 */
	public function add_mchat_category()
	{
		/** @var module $module */
		$module = $this->container->get('migrator.tool.module');

		if (!$module->exists('acp', 'ACP_CAT_DOT_MODS', 'ACP_CAT_MCHAT'))
		{
			$module->add('acp', 'ACP_CAT_DOT_MODS', 'ACP_CAT_MCHAT');
		}
	}
}
