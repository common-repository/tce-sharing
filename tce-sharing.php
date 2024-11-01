<?php

/**
 * @wordpress-plugin
 * Plugin Name:       TCE Sharing
 * Version:           2.0.9
 * Requires at least: 5.4
 * Requires PHP:      7.4
 * Plugin URI:        https://tce.exchange/en/wordpress-plugin
 * Description:       Connect to the TCE market place and join other publishers to reach a larger target audience for your content.
 * Author:            The Content Exchange BV
 * Author URI:        https://tce.exchange
 * License:           GPL v3
 * Text Domain:       tce-sharing
 */

/*
 * Copyright 2020 - The Content Exchange BV
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace TheContentExchange;

use TheContentExchange\Core\TheContentExchangeActivator;
use TheContentExchange\Core\TheContentExchangeDeactivator;
use TheContentExchange\Core\TheContentExchangeSharing;
use TheContentExchange\Core\TheContentExchangeUninstaller;

defined('ABSPATH') or die('Nope, not accessing this');

define('THE_CONTENT_EXCHANGE_BASE_PATH', plugin_dir_path(__FILE__));
define('THE_CONTENT_EXCHANGE_BASE_URL', plugin_dir_url(__FILE__));

$getenv = static function ($name) {
    return (false === getenv($name)) ? null : getenv($name);
};
define('THE_CONTENT_EXCHANGE_AWS_REGION', $getenv('AWS_REGION') ?? 'eu-central-1');
define('THE_CONTENT_EXCHANGE_AWS_USER_POOL', $getenv('AWS_USER_POOL') ?? 'eu-central-1_LftLoZpkr');
define('THE_CONTENT_EXCHANGE_AWS_USER_POOL_WEBCLIENT', $getenv('AWS_USER_POOL_WEBCLIENT') ?? '720hblf9v6l1kajumh3va4grmq');
define('THE_CONTENT_EXCHANGE_AWS_IDENTITY_POOL', $getenv('AWS_IDENTITY_POOL') ?? 'eu-central-1:c29b64f4-f2c3-4ff2-8e78-d68b5ea459dd');
define('THE_CONTENT_EXCHANGE_API_ENDPOINT', $getenv('TCE_API_ENDPOINT') ?? 'https://api.tce.exchange');
define('THE_CONTENT_EXCHANGE_UPLOAD_PATH', $getenv('TCE_UPLOAD_PATH') ?? '/news-contents');
define('THE_CONTENT_EXCHANGE_PLUGIN_VERSION', '2.0.9');
unset($getenv);

// include the Composer autoload file
require THE_CONTENT_EXCHANGE_BASE_PATH . 'Autoload/autoload.php';

// Activation, deactivation and uninstallation of the plugin
register_activation_hook(__FILE__, [new TheContentExchangeActivator(), 'tceActivate']);
register_deactivation_hook(__FILE__, [ new TheContentExchangeDeactivator(), 'tceDeactivate']);
register_uninstall_hook(__FILE__, [ TheContentExchangeUninstaller::class, 'tceUninstall']);

$tceSharing = new TheContentExchangeSharing();
$tceSharing->tceRun();
