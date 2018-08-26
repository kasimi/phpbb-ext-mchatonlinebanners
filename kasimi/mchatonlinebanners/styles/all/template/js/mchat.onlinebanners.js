/**
 *
 * mChat Online Banners. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2018 kasimi - https://kasimi.net
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

jQuery(function($) {

'use strict';

if (typeof mChat !== 'object') {
	return;
}

function Message($message) {
	this.message = $message;
}

Message.prototype.getTime = function() {
	return this.message.data('mchat-message-time') * 1000;
};

Message.prototype.getName = function() {
	return this.message.data('mchat-username');
};

Message.prototype.setBanner = function(banner) {
	this.message.attr('data-mchat-banner', banner);
};

Message.prototype.removeBanner = function() {
	this.message.removeAttr('data-mchat-banner');
};

function Banners(banners, timeDifference) {
	this.banners = banners;
	this.timeDifference = timeDifference;
	this.users = {};
}

Banners.prototype.status = function(age) {
	for (let i = 0; i < this.banners.length; i++) {
		 if (!this.banners[i].duration || this.banners[i].duration > age) {
			return this.banners[i];
		}
	}
	return false;
};

Banners.prototype.get = function(name) {
	if (!this.users.hasOwnProperty(name)) {
		this.users[name] = {
			name: name,
			timeout: 0,
			banner: 0,
			latestTime: 0,
			messages: $([])
		};
	}
	return this.users[name];
};

Banners.prototype.addMessages = function($messages) {
	let that = this;
	let banners = new Set();
	$messages.each(function() {
		let message = new Message($(this));
		let banner = that.get(message.getName());
		banner.messages = banner.messages.add(message);
		if (message.getTime() > banner.latestTime) {
			banner.latestTime = message.getTime();
			banners.add(banner);
		} else if (banner.banner) {
			message.setBanner(banner.banner);
		}
	});
	banners.forEach(this.update, this);
};

Banners.prototype.update = function(banner) {
	let age = Date.now() - banner.latestTime - this.timeDifference;
	let status = this.status(age);
	if (status) {
		let remaining = status.duration ? status.duration - age : 0;
		banner.banner = status.id;
		banner.messages.each(Message.prototype.setBanner, [banner.banner]);
		if (remaining) {
			clearTimeout(banner.timeout);
			banner.timeout = setTimeout(this.update.bind(this, banner), remaining);
		}
	} else {
		banner.messages.each(Message.prototype.removeBanner);
	}
};

mChat.onlineBanners = function(onlineBanners, timeDifference) {
	let banners = new Banners(onlineBanners, timeDifference);
	banners.addMessages($('.mchat-message'));

	$(mChat).on({
		mchat_add_message_before: function(e, data) {
			banners.addMessages(data.message);
		},
		mchat_edit_message_before: function(e, data) {
			banners.addMessages(data.newMessage);
		},
		mchat_rooms_enter_after: function(e, data) {
			let roomId = data.room.data('room-id');
			banners.addMessages(mChat.rooms.messages[roomId], true);
		}
	});
};

});
