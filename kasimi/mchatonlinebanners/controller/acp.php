<?php

/**
 *
 * mChat Online Banners. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2018 kasimi - https://kasimi.net
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace kasimi\mchatonlinebanners\controller;

use kasimi\mchatonlinebanners\includes\helper;
use phpbb\json_response;
use phpbb\language\language;
use phpbb\log\log_interface;
use phpbb\request\request_interface;
use phpbb\template\template;
use phpbb\user;

class acp extends base
{
	/** @var user */
	protected $user;

	/** @var language */
	protected $lang;

	/** @var request_interface */
	protected $request;

	/** @var template */
	protected $template;

	/** @var log_interface */
	protected $log;

	/** @var helper */
	protected $helper;

	/**
	 * @param user				$user
	 * @param language			$lang
	 * @param request_interface	$request
	 * @param template			$template
	 * @param log_interface		$log
	 * @param helper			$helper
	 */
	public function __construct(
		user $user,
		language $lang,
		request_interface $request,
		template $template,
		log_interface $log,
	 	helper $helper
	)
	{
		$this->user			= $user;
		$this->lang			= $lang;
		$this->request		= $request;
		$this->template		= $template;
		$this->log			= $log;
		$this->helper		= $helper;
	}

	/**
	 * @param string $id
	 * @param string $mode
	 * @param string $u_action
	 */
	public function main($id, $mode, $u_action)
	{
		$ext_name = 'kasimi/mchatonlinebanners';

		$this->tpl_name = 'acp_main_body';
		$this->page_title = 'MCHATONLINEBANNERS_TITLE';

		$this->lang->add_lang('acp', $ext_name);
		//$this->lang->add_lang('mchat_acp', 'dmzx/mchat');

		$action	= $this->request->variable('action', '');
		$banner_id = $this->request->variable('banner', 0);

		add_form_key($ext_name);

		switch ($action)
		{
			case 'add':

				$this->tpl_name = 'acp_add_edit_body';

				$this->template->assign_vars([
					'ACTION'	=> 'ADD',
					'U_BACK'	=> $u_action,
					'U_ACTION'	=> $u_action . '&amp;action=create',
				]);

				return;

			break;

			case 'create':

				if (!check_form_key($ext_name))
				{
					trigger_error($this->lang->lang('FORM_INVALID') . adm_back_link($u_action), E_USER_WARNING);
				}

				$banner = $this->get_banner_from_request($this->helper->get_next_banner_id());

				if ($this->helper->add_banner($banner))
				{
					$this->log('LOG_MCHATONLINEBANNERS_CREATED', $banner);

					trigger_error($this->lang->lang('MCHATONLINEBANNERS_CREATED') . adm_back_link($u_action));
				}

			break;

			case 'edit':

				$this->tpl_name = 'acp_add_edit_body';

				$banner = $this->helper->get_banner($banner_id);

				if ($banner)
				{
					$this->template->assign_vars([
						'BANNER_TITLE'		=> $banner['title'],
						'BANNER_COLOR'		=> $banner['color'],
						'BANNER_DURATION'	=> $banner['duration'],
					]);
				}

				$this->template->assign_vars([
					'ACTION'			=> 'EDIT',
					'U_BACK'			=> $u_action,
					'U_ACTION'			=> $u_action . '&amp;action=modify&amp;banner=' . $banner_id,
				]);

				return;

			break;

			case 'modify':

				if (!check_form_key($ext_name))
				{
					trigger_error($this->lang->lang('FORM_INVALID') . adm_back_link($u_action), E_USER_WARNING);
				}

				$banner = $this->get_banner_from_request($banner_id);

				if ($this->helper->set_banner($banner))
				{
					$this->log('LOG_MCHATONLINEBANNERS_MODIFIED', $banner);

					trigger_error($this->lang->lang('MCHATONLINEBANNERS_MODIFIED') . adm_back_link($u_action));
				}

			break;

			case 'up':
			case 'down':

				if (!check_link_hash($this->request->variable('hash', ''), 'mchatonlinebanner'))
				{
					trigger_error($this->lang->lang('FORM_INVALID') . adm_back_link($u_action), E_USER_WARNING);
				}

				$move_result = $this->helper->move_banner($banner_id, $action == 'up' ? -1 : 1);

				if ($this->request->is_ajax())
				{
					(new json_response())->send([
						'success' => $move_result,
					]);
				}

			break;

			case 'delete':

				if (confirm_box(true))
				{
					$delete_banner = $this->helper->delete_banner($banner_id);

					if ($delete_banner)
					{
						$this->log('LOG_MCHATONLINEBANNERS_DELETED', $delete_banner);
					}

					if ($this->request->is_ajax())
					{
						(new json_response())->send([
							'MESSAGE_TITLE'	=> $this->lang->lang('INFORMATION'),
							'MESSAGE_TEXT'	=> $this->lang->lang('MCHATONLINEBANNERS_DELETED'),
							'REFRESH_DATA'	=> ['time' => 3],
						]);
					}
				}
				else
				{
					confirm_box(false, $this->lang->lang('CONFIRM_OPERATION'), build_hidden_fields([
						'action'	=> 'delete',
						'banner_id'	=> $banner_id,
					]));
				}

			break;
		}

		$banners = $this->helper->get_banners();

		$hash = generate_link_hash('mchatonlinebanner');

		foreach ($banners as $banner)
		{
			$this->template->assign_block_vars('banners', [
				'TITLE'				=> $banner['title'],
				'COLOR'				=> $banner['color'],
				'DURATION'			=> $banner['duration'],
				'IS_TRANSLATABLE'	=> $this->helper->is_banner_translatable($banner),
				'U_MOVE_UP'			=> $u_action . '&amp;action=up&amp;banner=' . $banner['id'] . '&amp;hash=' . $hash,
				'U_MOVE_DOWN'		=> $u_action . '&amp;action=down&amp;banner=' . $banner['id'] . '&amp;hash=' . $hash,
				'U_EDIT'			=> $u_action . '&amp;action=edit&amp;banner=' . $banner['id'],
				'U_DELETE'			=> $u_action . '&amp;action=delete&amp;banner=' . $banner['id'],
			]);
		}

		$this->template->assign_var('U_ACTION', $u_action . '&amp;action=add');
	}

	/**
	 * @param int $banner_id
	 * @return array
	 */
	protected function get_banner_from_request($banner_id)
	{
		return [
			'id'		=> $banner_id,
			'title'		=> $this->request->variable('banner_title', '', true),
			'color'		=> $this->validate_banner_color($this->request->variable('banner_no_color', 0) ? '' : '#' . $this->request->variable('banner_color', '')),
			'duration'	=> max(0, $this->request->variable('banner_duration', 0)),
		];
	}

	/**
	 * @param string $color
	 * @return string
	 */
	protected function validate_banner_color($color)
	{
		return $color && preg_match('/^#[a-f0-9]{6}$/i', $color) ? $color : '';
	}

	/**
	 * @param string $log_operation
	 * @param array $banner
	 */
	protected function log($log_operation, array $banner)
	{
		$this->log->add('admin', $this->user->data['user_id'], $this->user->ip, $log_operation, false, [$banner['title'] ?: $banner['color']]);
	}
}
