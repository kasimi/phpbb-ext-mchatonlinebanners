<?php

/**
 *
 * mChat Online Banners. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2018 kasimi - https://kasimi.net
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace kasimi\mchatonlinebanners\event;

use kasimi\mchatonlinebanners\includes\helper;
use phpbb\event\data;
use phpbb\language\language;
use phpbb\template\template;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class main_listener implements EventSubscriberInterface
{
	/** @var language */
	protected $lang;

	/** @var template  */
	protected $template;

	/** @var helper */
	protected $helper;

	/**
	 * @param language	$lang
	 * @param template	$template
	 * @param helper	$helper
	 */
	public function __construct(
		language $lang,
		template $template,
		helper $helper
	)
	{
		$this->lang		= $lang;
		$this->template	= $template;
		$this->helper	= $helper;
	}

	/**
	 * @return array
	 */
	public static function getSubscribedEvents()
	{
		return [
			'dmzx.mchat.global_text_settings_modify'	=> 'mchat_global_text_settings_modify',
			'dmzx.mchat.render_page_after'				=> 'mchat_render_page_after',
		];
	}

	/**
	 * @param data $event
	 */
	public function mchat_global_text_settings_modify($event)
	{
		$event['global_text_settings'] = array_merge($event['global_text_settings'], [
			'mchat_online_banners' => ['default' => helper::get_empty_banners()],
		]);
	}

	/**
	 *
	 */
	public function mchat_render_page_after()
	{
		$banners = $this->helper->get_banners();

		// Only include the language file if there's a banner with a translatable title
		if (array_filter($banners, [$this->helper, 'is_banner_translatable']))
		{
			$this->lang->add_lang('common', 'kasimi/mchatonlinebanners');
		}

		$this->template->assign_vars([
			'MCHAT_ONLINEBANNERS'		=> $banners,
			'MCHAT_ONLINEBANNERS_NOW'	=> round(microtime(true) * 1000),
		]);
	}
}
