<?php
/* This file is part of Jeedom.
 *
 * Jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
 */
require_once dirname(__FILE__) . '/../../../core/php/core.inc.php';

function homebridge_install(){
	exec('echo "Installation du Plugin Homebridge" >> '.log::getPathToLog(__CLASS__ . '_api'));
	$MobileExists=true;
	try {
		$pluginMobile = plugin::byId('mobile');
	} catch(Exception $e) {
		$mobileExists=false;
	}
	if(mobileExists) {
		exec('echo "Présence du plugin Mobile [High Five old friend]" >> '.log::getPathToLog(__CLASS__ . '_api'));
		$pluginMobile->deamon_stop();
		
		$user_homebridge = config::byKey('user_homebridge','homebridge');
		$user_mobile = config::byKey('user_homebridge','mobile');
		if($user_mobile && !$user_homebridge) {
			config::save('user_homebridge',$user_mobile,'homebridge');
			$user_homebridge = $user_mobile;
			exec('echo "Reprise du User de mobile:'.$user_mobile.'" >> '.log::getPathToLog(__CLASS__ . '_api'));
		}

		$pin_homebridge = config::byKey('pin_homebridge','homebridge');
		$pin_mobile = config::byKey('pin_homebridge','mobile');
		if($pin_mobile && !$pin_homebridge) {
			config::save('pin_homebridge',$pin_mobile,'homebridge');
			$pin_homebridge = $pin_mobile;
			exec('echo "Reprise du PIN de mobile:'.$pin_mobile.'" >> '.log::getPathToLog(__CLASS__ . '_api'));
		}

		$name_homebridge = config::byKey('name_homebridge','homebridge');
		$name_mobile = config::byKey('name_homebridge','mobile');
		if($name_mobile && !$name_homebridge) {
			config::save('name_homebridge',$name_mobile,'homebridge');
			$name_homebridge = $name_mobile;
			exec('echo "Reprise du Nom de mobile:'.$name_mobile.'" >> '.log::getPathToLog(__CLASS__ . '_api'));
		}

		$mac_homebridge = config::byKey('mac_homebridge','homebridge');
		$mac_mobile = config::byKey('mac_homebridge','mobile');
		if($mac_mobile && !$mac_homebridge) {
			config::save('mac_homebridge',$mac_mobile,'homebridge');
			$mac_homebridge = $mac_mobile;
			exec('echo "Reprise de la MAC de mobile:'.$mac_mobile.'" >> '.log::getPathToLog(__CLASS__ . '_api'));
		}
		
		$platform_homebridge = dirname(__FILE__).'/../data/otherPlatform.json';
		$platform_mobile = dirname(__FILE__).'/../../mobile/data/otherPlatform.json';

		if(file_exists($platform_mobile) && file_exists($platform_homebridge)) {
			if(filemtime($platform_mobile) > filemtime($platform_homebridge)) {
				exec('echo "Fichier de plateforme Mobile plus récent, on le reprend" >> '.log::getPathToLog(__CLASS__ . '_api'));
				exec('yes | ' . system::getCmdSudo() . ' cp '.$platform_mobile.' '.$platform_homebridge.' >> ' . log::getPathToLog(__CLASS__) . '_api 2>&1 ');	
			}
		} else if(file_exists($platform_mobile) && !file_exists($platform_homebridge)) {
			exec('echo "Fichier de plateforme Mobile préexistant, on le reprend" >> '.log::getPathToLog(__CLASS__ . '_api'));
			exec('yes | ' . system::getCmdSudo() . ' cp '.$platform_mobile.' '.$platform_homebridge.' >> ' . log::getPathToLog(__CLASS__) . '_api 2>&1 ');	
		}
		
		$AccessoryInfoMobile = dirname(__FILE__).'/../../mobile/resources/homebridge/persist/AccessoryInfo.'.$mac_mobile.'.json';
		$AccessoryInfoHomebridge = dirname(__FILE__).'/../resources/homebridge/persist/AccessoryInfo.'.$mac_homebridge.'.json';
		if(file_exists($AccessoryInfoMobile) && !file_exists($AccessoryInfoHomebridge)) {
			exec('echo "Fichier AccessoryInfo de Mobile préexistant, on le reprend" >> '.log::getPathToLog(__CLASS__ . '_api'));
			exec('yes | ' . system::getCmdSudo() . ' cp '.$AccessoryInfoMobile.' '.$AccessoryInfoHomebridge.' >> ' . log::getPathToLog(__CLASS__) . '_api 2>&1 ');
		}
		
		$IdentifierCacheMobile = dirname(__FILE__).'/../../mobile/resources/homebridge/persist/IdentifierCache.'.$mac_mobile.'.json';
		$IdentifierCacheHomebridge = dirname(__FILE__).'/../resources/homebridge/persist/IdentifierCache.'.$mac_homebridge.'.json';
		if(file_exists($IdentifierCacheMobile) && !file_exists($IdentifierCacheHomebridge)) {
			exec('echo "Fichier IdentifierCache de Mobile préexistant, on le reprend" >> '.log::getPathToLog(__CLASS__ . '_api'));
			exec('yes | ' . system::getCmdSudo() . ' cp '.$IdentifierCacheMobile.' '.$IdentifierCacheHomebridge.' >> ' . log::getPathToLog(__CLASS__) . '_api 2>&1 ');
		}
	}
	
	homebridge::uninstallHomebridge();
	exec('echo "Installation des dépendances" >> '.log::getPathToLog(__CLASS__ . '_api'));
	$pluginHomebridge = plugin::byId('homebridge');
	$pluginHomebridge->dependancy_install();
}

function homebridge_update(){
	exec('echo "Mise à jour du Plugin Homebridge" >> '.log::getPathToLog(__CLASS__ . '_api'));
	$pluginHomebridge = plugin::byId('homebridge');
	$pluginHomebridge->dependancy_install();
}
?>
