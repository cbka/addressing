<?php
/*
  ----------------------------------------------------------------------
  GLPI - Gestionnaire Libre de Parc Informatique
  Copyright (C) 2003-2008 by the INDEPNET Development Team.
  
  http://indepnet.net/   http://glpi-project.org/
  ----------------------------------------------------------------------
  
  LICENSE
  
  This file is part of GLPI.
  
  GLPI is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation; either version 2 of the License, or
  (at your option) any later version.
  
  GLPI is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.
  
  You should have received a copy of the GNU General Public License
  along with GLPI; if not, write to the Free Software
  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
  ------------------------------------------------------------------------
 */

// ----------------------------------------------------------------------
// Original Author of file: GRISARD Jean Marc & CAILLAUD Xavier
// Purpose of file:
// ----------------------------------------------------------------------

if (!defined('GLPI_ROOT')){
	die("Sorry. You can't access directly to this file");
	}

function plugin_addressing_dropdownSubnet($entity) {

	GLOBAL $DB;
	
	$rand=mt_rand();
	echo "<select name='_subnet' id='plugaddr_subnet' onChange='plugaddr_ChangeList();'>";
	echo "<option value=''>-----</option>";

	$sql="SELECT DISTINCT `subnet`, `netmask` 
			FROM `glpi_networking_ports` " .
			"LEFT JOIN `glpi_computers` ON (`glpi_computers`.`ID` = `glpi_networking_ports`.`on_device`) " .
			"WHERE `device_type` = '".COMPUTER_TYPE."' 
			AND `FK_entities` = '".$entity."' 
			AND `subnet` NOT IN ('','0.0.0.0','127.0.0.0') 
			AND `netmask` NOT IN ('','0.0.0.0','255.255.255.255')" .
			getEntitiesRestrictRequest(" AND ","glpi_computers","FK_entities",$entity) .
			"ORDER BY INET_ATON(`subnet`)";
	$result=array();
	$result[0]="-----";
	$res=$DB->query($sql);
	if ($res) while ($row=$DB->fetch_assoc($res)) {
		$val = $row["subnet"]."/".$row["netmask"];
		echo "<option value='$val'>$val</option>";
	}
	echo "</select>\n";			
}
?>