<?php  $data='{ "code": 1000, "msg": "操作成功", "data": {"transferIn":888,"balance":5000}}';

	$d= json_decode($data,true);

	var_dump($d['code']);