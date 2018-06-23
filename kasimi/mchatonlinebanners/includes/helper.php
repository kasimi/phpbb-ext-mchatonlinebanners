<?php

/**
 *
 * mChat Online Banners. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2018 kasimi - https://kasimi.net
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

namespace kasimi\mchatonlinebanners\includes;

use dmzx\mchat\core\settings;

class helper
{
	/** @var string */
	protected $root_path;

	/** @var settings */
	protected $mchat_settings;

	/**
	 * @param string	$root_path
	 * @param settings	$mchat_settings
	 */
	public function __construct(
		$root_path,
		settings $mchat_settings = null
	)
	{
		$this->root_path		= $root_path;
		$this->mchat_settings	= $mchat_settings;
	}

	/**
	 * @return bool
	 */
	public function is_mchat_enabled()
	{
		return $this->mchat_settings !== null;
	}

	/**
	 * @param int $banner_id
	 * @param array $banners
	 * @return bool|int
	 */
	public function find_index($banner_id, array $banners)
	{
		foreach ($banners as $i => $banner)
		{
			if ($banner['id'] == $banner_id)
			{
				return $i;
			}
		}

		return false;
	}

	/**
	 * @param array $banner
	 * @return bool|array
	 */
	public function add_banner(array $banner)
	{
		$banners = $this->get_banners();

		if ($this->find_index($banner['id'], $banners) !== false)
		{
			return false;
		}

		$banners[] = $banner;

		$this->set_banners($banners);

		return $banner;
	}

	/**
	 * @param int $banner_id
	 * @return bool|array
	 */
	public function delete_banner($banner_id)
	{
		$banners = $this->get_banners();

		$index = $this->find_index($banner_id, $banners);

		if ($index === false)
		{
			return false;
		}

		$deleted_banner = $banners[$index];

		unset($banners[$index]);

		$this->set_banners($banners);

		return $deleted_banner;
	}

	/**
	 * @param int $banner_id
	 * @return bool|array
	 */
	public function get_banner($banner_id)
	{
		$banners = $this->get_banners();

		$index = $this->find_index($banner_id, $banners);

		return $index === false ? false : $banners[$index];
	}

	/**
	 * @return array
	 */
	public function get_banners()
	{
		$banners = array_values(@json_decode($this->mchat_settings->cfg('mchat_online_banners'), true)) ?: [];

		foreach ($banners as &$banner)
		{
			$banner['title_raw'] = htmlspecialchars_decode($banner['title'], ENT_COMPAT);
			$banner['image_absolute'] = !$banner['image'] || strpos($banner['image'], 'http') === 0 ? $banner['image'] : $this->root_path . $banner['image'];
		}

		return $banners;
	}

	/**
	 * @param array $banner
	 * @return bool|array
	 */
	public function set_banner(array $banner)
	{
		$banners = $this->get_banners();

		$index = $this->find_index($banner['id'], $banners);

		if ($index === false)
		{
			return false;
		}

		$banners[$index] = $banner;

		$this->set_banners($banners);

		return $banner;
	}

	/**
	 * @param array $banners
	 */
	public function set_banners(array $banners)
	{
		foreach ($banners as &$banner)
		{
			unset($banner['title_raw']);
			unset($banner['image_absolute']);
		}

		$this->mchat_settings->set_cfg('mchat_online_banners', @json_encode(array_values($banners)) ?: []);
	}

	/**
	 * @param int $banner_id
	 * @param int $offset
	 * @return bool|array
	 */
	public function move_banner($banner_id, $offset)
	{
		$banners = $this->get_banners();

		$index = $this->find_index($banner_id, $banners);

		if ($index === false)
		{
			return false;
		}

		$banner = $banners[$index];

		$temp = array_splice($banners, $index, 1);

		array_splice($banners, max(0, $index + $offset), 0, $temp);

		$this->set_banners($banners);

		return $banner;
	}

	/**
	 * @return int
	 */
	public function get_next_banner_id()
	{
		$banners = $this->get_banners();

		$next_id = 0;

		foreach ($banners as $banner)
		{
			$next_id = max($banner['id'], $next_id);
		}

		return $next_id + 1;
	}

	/**
	 * @param array $banner
	 * @return bool
	 */
	public function is_banner_translatable(array $banner)
	{
		return (bool) preg_match('/^[0-9A-Z_]+$/', $banner['title']);
	}

	/**
	 * @return string
	 */
	public static function get_empty_banners()
	{
		return json_encode([]);
	}
}
