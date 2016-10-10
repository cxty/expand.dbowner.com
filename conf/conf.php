<?php
//数据库配置
$config['DB_TYPE']					='mysql';							//数据库类型
$config['DB_HOST']					='192.168.0.198';//'192.168.0.254';					//数据库主机
$config['DB_USER']					='expand_db';//'cxty';							//数据库用户名
$config['DB_PWD']					='g0xq5hx0r';//'1q2w3e';							//数据库密码
$config['DB_PORT']					=3306;							//数据库端口，mysql默认是3306
$config['DB_NAME']					='expand_db';				//数据库名
$config['DB_CHARSET']				='utf8';						//数据库编码
$config['DB_PREFIX']				='';						//数据库前缀
$config['DB_PCONNECT']				=false;						//true表示使用永久连接，false表示不适用永久连接，一般不使用永久连接

$config['DB_CACHE_ON']				=false;						//是否开启数据库缓存，true开启，false不开启
$config['DB_CACHE_PATH']			='./cache/db_cache/';		//数据库查询内容缓存目录，地址相对于入口文件
$config['DB_CACHE_TIME']			=600;						//缓存时间,0不缓存，-1永久缓存
$config['DB_CACHE_CHECK']			=true;						//是否对缓存进行校验
$config['DB_CACHE_FILE']			='cachedata';				//缓存的数据文件名
$config['DB_CACHE_SIZE']			='15M';						//预设的缓存大小，最小为10M，最大为1G
$config['DB_CACHE_FLOCK']			=true;						//是否存在文件锁，设置为false，将模拟文件锁


//OAuth2 站内访问
$config['oauth']['client_id']      = 'app16';
$config['oauth']['client_secret']  = '3fa71b9b287c30d42be3ef98abd3cd74';
$config['oauth']['redirect_uri']   = 'http://expand.dbowner.com/login/callback';
$config['oauth']['authorizeURL']   = 'https://auth.dbowner.com/oauth/authorize';
$config['oauth']['accessTokenURL'] = 'https://auth.dbowner.com/oauth/token2';
$config['oauth']['host']           = 'https://auth.dbowner.com';
// $config['oauth']['authorizeURL']   = 'http://user.dbowner.com/oauth/authorize';
// $config['oauth']['accessTokenURL'] = 'http://user.dbowner.com/oauth/token2';
// $config['oauth']['host']           = 'http://user.dbowner.com';
