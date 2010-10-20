<?php

/**
 * This file is part of the Froxlor project.
 * Copyright (c) 2003-2009 the SysCP Team (see authors).
 * Copyright (c) 2010 the Froxlor Team (see authors).
 *
 * For the full copyright and license information, please view the COPYING
 * file that was distributed with this source code. You can also view the
 * COPYING file online at http://files.froxlor.org/misc/COPYING.txt
 *
 * @copyright  (c) the authors
 * @author     Florian Lippert <flo@syscp.org> (2003-2009)
 * @author     Froxlor team <team@froxlor.org> (2010-)
 * @license    GPLv2 http://files.froxlor.org/misc/COPYING.txt
 * @package    Functions
 * @version    $Id$
 */

function storeSettingIpAddress($fieldname, $fielddata, $newfieldvalue, $server_id = 0)
{
	$returnvalue = storeSettingField($fieldname, $fielddata, $newfieldvalue, $server_id);

	if($returnvalue !== false && is_array($fielddata) && isset($fielddata['settinggroup']) && $fielddata['settinggroup'] == 'system' && isset($fielddata['varname']) && $fielddata['varname'] == 'ipaddress')
	{
		if($server_id > 0)
		{
			$client = froxlorclient::getInstance(null, $db, $server_id);
			$mysqlhosts = $client->getSetting('system', 'mysql_access_host');
		} else {
			$mysqlhosts = getSetting('system', 'mysql_access_host');
		}
		$mysql_access_host_array = array_map('trim', explode(',', $mysqlhosts));
		$mysql_access_host_array[] = $newfieldvalue;
		$mysql_access_host_array = array_unique(array_trim($mysql_access_host_array));
		$mysql_access_host = implode(',', $mysql_access_host_array);
		correctMysqlUsers($mysql_access_host_array);
		saveSetting('system', 'mysql_access_host', $mysql_access_host);
	}
	
	return $returnvalue;
}

?>