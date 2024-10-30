<?php

/*  Copyright 2010 BisonTech. All Rights Reserved.

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation; either version 2 of the License, or
  (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details:
  http://www.gnu.org/licenses/gpl-2.0.html
 */

/*
  Plugin Name: LogoReplacer
  Plugin URI: http://wordpress.org/extend/plugins/logoReplacer/
  Description: Replace registration and admin Logo
  Author:  http://www.BisonTech.net
  Version: 2.0.1
 */


add_action('plugins_loaded', 'lr_init');

define('LOGO_DIR', dirname(__FILE__) . '/logos/');


class LRLogoImg {

    public $custom_path = NULL;
    public $installed_path = NULL;

    function __construct($sCustomPath, $sInstalledPath) {

        $this->installed_path = $sInstalledPath;
        $this->custom_path = $sCustomPath;
    }

    function exec() {
		if (file_exists($this->custom_path)){
			$md5_custom = md5_file($this->custom_path);
		}
		if (file_exists($this->installed_path)){
			$md5_installed = md5_file($this->installed_path);
		}
		if ($md5_custom ==false || $md5_installed == false){
			return;
		}
        if ($md5_custom != $md5_installed) {
            lr_installLogo($this->custom_path, $this->installed_path);
        }
    }

}

function lr_init() {

   
    $aImgList = array();
    $aImgList[ABSPATH . 'wp-admin/images/logo-login.png'] = LOGO_DIR . 'logo-login.png';
    $aImgList[ABSPATH . 'wp-admin/images/logo-login.gif'] = LOGO_DIR . 'logo-login.gif';
    $aImgList[ABSPATH . 'wp-admin/images/wp-logo-vs.png'] = LOGO_DIR . 'wp-logo-vs.png';
    $aImgList[ABSPATH . 'wp-admin/images/wp-logo.png'] = LOGO_DIR . 'wp-logo.png';
	$aImgList[ABSPATH . 'wp-admin/images/wordpress-logo.png'] = LOGO_DIR . 'wordpress-logo.png';
    $aImgList[ABSPATH . 'wp-includes/images/admin-bar-sprite.png'] = LOGO_DIR . 'admin-bar-sprite.png';

    foreach ($aImgList as $sInstalledPath => $sCustomPath) {       
        if (file_exists($sCustomPath)) {
            $oLogo = new LRLogoImg($sCustomPath, $sInstalledPath);
            $oLogo->exec();
        }
    }
}

function lr_installLogo($src, $dst) {
    if (is_writable($dst)) {
        if (file_exists($src)) {
            if (!copy($src, $dst)) {
                if (WP_DEBUG) {
                    echo "Failed to copy $src...";
                }
            }
        } else {
            if (WP_DEBUG) {
                echo 'File not exist';
            }
        }
    } else {
        if (WP_DEBUG) {
            echo 'Please make wp-admin directory writable!';
        }
    }
}

?>