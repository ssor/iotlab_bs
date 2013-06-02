<?php
return array(
	//'配置项'=>'配置值'
	'DB_TYPE'=>'pdo',
	'DB_DSN' => 'sqlite:'.dirname(__FILE__).'/test.db3', //相对于config目录的路径
	'DB_NAME'=>'test.db3',
	// 'WS_LOCATION' => 'ws://192.168.1.102:4649'
	'WS_LOCATION' => 'ws://192.168.1.100:4649'
	// 'WS_LOCATION' => 'ws://192.168.248.97:4649'

);
?>