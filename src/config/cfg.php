<?php
$cfg['db_host'] = 'localhost';
$cfg['db_prefix'] = getenv('OLM_DB_PREFIX') ? getenv('OLM_DB_PREFIX') : 'olm_';
$cfg['db_name'] = getenv('OLM_DB_NAME') ? getenv('OLM_DB_NAME') : 'olm';
$cfg['db_user'] = getenv('OLM_DB_USER') ? getenv('OLM_DB_USER') : 'olm';
$cfg['db_password'] = getenv('OLM_DB_PASSWORD') ? getenv('OLM_DB_PASSWORD') : 'olm';
$cfg['jwt_secret'] = 'secretive_secret';
$cfg['jwt_lifetime'] = 86400;