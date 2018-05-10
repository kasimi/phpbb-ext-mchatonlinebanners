<?php

/**
 *
 * mChat Online Banners. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2018 kasimi - https://kasimi.net
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = [];
}

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine
//
// Some characters for use
// ’ » “ ” …

$lang = array_merge($lang, [
	'MCHATONLINEBANNERS_TITLE'						=> 'mChat Online Banners',
	'MCHATONLINEBANNERS_EXPLAIN'					=> 'Here you can configure banners for your mChat users. They are assigned to the users from top to bottom, depending on how long ago their last mChat message was sent.',

	'MCHATONLINEBANNERS_ADD'						=> 'Add banner',
	'MCHATONLINEBANNERS_EDIT'						=> 'Edit banner',

	'MCHATONLINEBANNERS_CREATED'					=> 'Your mChat Online Banner was added.',
	'MCHATONLINEBANNERS_MODIFIED'					=> 'Your mChat Online Banner was updated.',
	'MCHATONLINEBANNERS_DELETED'					=> 'The selected mChat Online Banner was removed.',

	'MCHATONLINEBANNERS_BANNER_TITLE'				=> 'Title',
	'MCHATONLINEBANNERS_BANNER_TITLE_EXPLAIN'		=> 'The title is displayed when hovering a user’s banner. Leave the field empty to not display anything when hovering. This field is translatable. Enter a language key here and provide the translations in the extension’s <em>language/*/common.php</em> files.',
	'MCHATONLINEBANNERS_BANNER_TITLE_TRANSLATABLE'	=> 'This is a language key. The title will be translated to each user’s individual language.',
	'MCHATONLINEBANNERS_BANNER_COLOR'				=> 'Colour',
	'MCHATONLINEBANNERS_BANNER_COLOR_EXPLAIN'		=> 'The colour of the banner. If you select no color, there will be no banner at all for the given duration.',
	'MCHATONLINEBANNERS_BANNER_NO_COLOR'			=> 'No colour',
	'MCHATONLINEBANNERS_BANNER_DURATION'			=> 'Duration',
	'MCHATONLINEBANNERS_BANNER_DURATION_EXPLAIN'	=> 'The time in seconds this banner is displayed. After the time has passed without any activity from the user, the user’s banner changes to the next banner in the list. If set to 0, the banner is displayed indefinitely.',
	'MCHATONLINEBANNERS_DURATION_SECONDS'			=> 'seconds',
	'MCHATONLINEBANNERS_DURATION_SECONDS_FULL'		=> [
		0 => '∞',
		1 => '%d second',
		2 => '%d seconds',
	],
]);
