<?php

class testMod extends commonMod {
	public function get () {
		$tmp = array(
				'key1_1' => 'a1_1',
				'key1_2' => array(
						'key2_1' => 'a2_1',
						'key2_2' => array(
								'key3_1' => 'a3_1',
								'key3_2' => 'a3_2',
								'key3_3' => array(
										'key4_1' => 'a4_1',
										'key4_2' => 'a4_2',
										'key4_3' => array(
												'key5_1' => 'a5_1'
												)
										)
								),
						),
						'key2_3' => 'a2_3',
				);
		ComFun::pr($tmp);

		if ( $tmp ) {
			foreach ( $tmp as $k => $v ) {
				if ( is_array($v) ) {
					$re[] = $this->_loop($v, $k);
				} else {
					$re[] = $k;
				}
			}
		}

		$key = implode('|', $re);

		ComFun::pr($key);

		//
		exit;
	}
	private function _loop ( $params, $kName ) {
		if ( $params ) {
			$str = '';
			foreach ( $params as $k => $v ) {
				if ( is_array($v) ) {
					$str .= $str ? '|' . $this->_loop( $v,  $kName . '.' . $k) : $this->_loop( $v,  $kName . '.' . $k);//implode('|', );
				} else {
					$str .= $str ? '|' . $kName . '.' . $k : $kName . '.' . $k;
				}
			}
			return $str ;
		}
	}
	
	public function mysql () {
		//exit;
		set_time_limit(0);
		
		echo 'nowtime:' . strtotime(date('Y-m-d')) . '<br />';
		echo date('Y-m-d H:i:s') . '<br>';
		
		$tArr['data_time'] = mktime(11,0,0,'08','20','2013');
		$tArr['data_time'] = date('Y-m-d H',time());
		$tArr['data_time'] = '1401461235';
		$tArr['data_type'] = 'h';
		$tArr['data_hour'] = 11;
		$tArr['data_hour'] = 8;
		
		$tArr['data_test'] = true;
		
		//$tArr['page']  = 1;
		//$tArr['pagesize'] = 10;
	
		ComFun::pr($tArr);
		exit;
		$url = 'http://www.jd.com';
		//$url = 'http://www.taobao.com';
		//$url = 'http://www.sina.com.cn';
		//$url = 'http://sports.sina.com.cn/nba/';
		//$url = 'http://analysis.dbowner.com/report';

		//$dbClassName = $this->getClass('DB_AnaDataSource');
		//$rb = $dbClassName->addURLInfo($tArr);
		//$rb = $dbClassName->addClientLogInfo($tArr);
		//$rb = $dbClassName->addPageStaticsInfo($tArr);
		//$rb = $dbClassName->addPageStaticsLogInfo($tArr);
	//	$rb = $dbClassName->updateShortUrlKeywork($tArr);
	
	//ComFun::pr($rb);	
	
	//echo date('Y-m-d H:i:s') . '<br>';
	//exit;
		$dbAnaData = $this->getClass('DB_AnaData');
		$dbAnaData->anaPushChannelNewUser($tArr);
		ComFun::pr($rb);
		exit;
		$dbAnaShortUrl = new DB_AnaShortUrl($this->model, $this->mongo_model);
		
		//小时分析
		$rb = $dbAnaShortUrl->anaShortUrlPV($tArr); //浏览量PV分析
		//$rb = $dbAnaShortUrl->anaShortUrlUV($tArr); //浏览量PV分析
		//$rb = $dbAnaShortUrl->anaShortUrlIP($tArr); //地域详情
		//$rb = $dbAnaShortUrl->anaShortUrlBrowser($tArr); //浏览器
		//$rb = $dbAnaShortUrl->anaShortUrlSystem($tArr); //系统
		//$rb = $dbAnaShortUrl->anaShortUrlKeyword($tArr); //关键词

		exit;
		$dbAnaData = new DB_AnaData($this->model, $this->mongo_model);
		$rb = $dbAnaData->anaPushChannelNewUser($tArr);
		ComFun::pr($rb);
		echo date('Y-m-d H:i:s') . '<br>';
	}
	public function listen () {
		echo 2;
	}
	public function test () {
		$rb = $this->mongo_model->table('tbShortURLInfo')->count();
		ComFun::pr($rb);
		exit;
		//exit;

		/*
		$tArr['data_time'] = mktime(0,0,0,'04','15','2014');
		$tArr['data_hour'] = 11;
		
		$startTime = $tArr['data_time']+3600*$tArr['data_hour'];
		$endTime   = $tArr['data_time']+3600*$tArr['data_hour']+3599;
		
		$cond = array(
// 				'tbClientLogInfo' => array(
// 						'$exists' => 1
// 						),
				'URLID' => 2,
			//	'uOrigin' => 2,
				//'uAppendTime' => 1370762145,
// 				'uKeyword' => array(
// 						'$ne' => ''
// 						),
// 				'tbClientLogInfo.cAppendTime' => array(
// 						'$gte' => $startTime,
// 						'$lte' => $endTime
// 				)
				);
		$field = array(
				'URLID' => 1,
				'URLStr' => 1,
				'uOrigin' => 1,
				'uAppendTime' => 1,
				//'uKeyword' => 1
				);
		$rb = $this->mongo_model->table('tbShortURLInfo')->field($field)->cond($cond)->offset(1)->limit(10)->findAll();
		ComFun::pr($rb);

		if ( $rb ) {
			foreach ( $rb as $v ) {
				if ( $v['tbClientLogInfo'] ) {
					foreach ( $v['tbClientLogInfo'] as $v2 ) {
// 						if ( $v2['cAppendTime'] >= $startTime && $v2['cAppendTime'] <= $endTime  ) {
// 							echo $v['URLID'] . '#' . date('Y-m-d H:i:s', $_v_2['uAppendTime']) . '<br>';
// 							//ComFun::pr(ComFun::getbrowsess($v2['cData']['HTTP_USER_AGENT']));
// 							//echo $v2['cData']['HTTP_USER_AGENT'];
// 							//$browser = new Browser($v2['cData']['HTTP_USER_AGENT']);
// 							//echo $browser->getBrowser() . '  ' . $browser->getVersion() . ' ' . $browser->getPlatform() . ' ' . $browser->getPlatformDetail();
// 							//ComFun::pr(ComFun::getbrowser($_SERVER['HTTP_USER_AGENT']));
// 							echo '<br>';
							
// 						}
						if ( $v2['cAppendTime']  ) {
							echo $v2['URLID'] . '#' . date('Y-m-d H:i:s', $v2['cAppendTime']) . '<br>';
							//ComFun::pr(ComFun::getbrowsess($v2['cData']['HTTP_USER_AGENT']));
							//echo $v2['cData']['HTTP_USER_AGENT'];
							//$browser = new Browser($v2['cData']['HTTP_USER_AGENT']);
							//echo $browser->getBrowser() . '  ' . $browser->getVersion() . ' ' . $browser->getPlatform() . ' ' . $browser->getPlatformDetail();
							//ComFun::pr(ComFun::getbrowser($_SERVER['HTTP_USER_AGENT']));
							echo '<br>';
								
						}
						
					}
				}
			}
		}
		exit;
		*/
		$field = array(
				'PushClientID' => 1,
				'pcAppendTime' => 1,
//				'pcAppID' => 1,
				'pcChannel' => 1,
				'tbPushClientDataInfo' => 1
// 				'tbPushAppOperaInfo.aoAction.ViewTrack.Code' => 1,
// 				'tbPushAppOperaInfo.aoAction.ViewTrack.w' => 1,
// 				'tbPushAppOperaInfo.aoAction.ViewTrack.h' => 1,
// 				'tbPushAppOperaInfo.aoAction.ViewTrack.Data' => 1,
 //				'tbPushAppOperaInfo.aoAppendTime' => 1,
				);
		
// 		$_cond = array(
// 				'pcChannel' => 1
// 				);
		$_cond = array(
				'pcAppendTime' => array(
						'$gte' => mktime(0,0,0,'04','08','2014'),
						'$lte' => mktime(0,0,0,'04','08','2014')
				)
		);
		
		$re = $this->mongo_model->table('tbPushClientInfo')->asc('pcAppendTime')->offset(1)->limit(10)->noPk(true)->findAll();
		ComFun::pr($re);exit;
		if ( $re ) {
			foreach ( $re as $_v ) {
				echo date('Y-m-d H:i:s', $_v['pcAppendTime']) . '<br>';
				if ( $_v['tbPushAppOperaInfo'] ) {
					foreach ( $_v['tbPushAppOperaInfo'] as $_v_2 ) {
						echo date('Y-m-d H:i:s', $_v_2['aoAppendTime']) . '<br>';
					}
				}
			}
		}
		
		exit;

// 		$url = 'http://tanalysis.dbowner.com/fixed/day?data_object=collect_push&data_method=addPushAppOperaInfo&data_time=1376841600&ACTIDlist=1395818976ye3zqskn';
		
// 		$tArr['data_object'] = 'collect_push';
// 		$tArr['data_method'] = 'addPushAppOperaInfo';
// 		$tArr['data_time'] = 1376841600;
// 		$tArr['ACTIDlist'] = '1395818976ye3zqskn';
// 		$dbClassName = $this->getClass('DB_AnaDataSource');
// 		$dbClassName->addPushAppOperaInfo($tArr);
		
// 		exit;
		
		
	}
	public function testreport () {
		//exit;
		//$tArr['anAppID'] = 'app5';
		$tArr['data_interval'] = 30;
		$tArr['anDateTime'] = mktime(0,0,0,'04','18','2014');;
		$tArr['pagesize'] = 10;
		$tArr['page'] = 1;
		$tArr['AutoID'] = 3;
		$tArr['anKeyword'] = '"屏幕截图","App"';
		//$dbAnaGetResult = $this->getClass('DB_AnaGetResult');
		ComFun::pr($tArr);
		$dbAnaShortUrlResult = new DB_AnaShortUrlResult($this->model);
		$re = $dbAnaShortUrlResult->getShortUrlKeyword( $tArr );
		ComFun::pr($re);
	}
	public function mongo () {
		exit;
// 		$ip1_str = '145.197.47.171';
// 		$ip_arr = explode(".",$ip1_str);   // 使用一个字符串分割另一个字符串以.来区分
// 		$ip1_str = 0;  //初始化
// 		foreach($ip_arr as $i=>$s){
// 			$ip1_str += $s*pow(256,3-$i);  //幂次方函数
// 		}
// 		echo $ip1_str.'<br>';
		
// 		echo intval(ip2long($ip1_str)) . '<br>';
		
// 		echo bindec(decbin(ip2long($ip1_str))) . '<br>';
// 		exit;
// 		echo 1389777632-86400*30;
// 		echo '<br>';
// 		echo date('Y-m-d H:i:s', 1389777632-86400*30) . '<Br>';
		//exit;
		//$datetime = mktime(0,0,0,'01','15','2014');
		$datetime = mktime(12,12,12,'03','10','2014');
		//$data_hour = 17;
		//echo date('Y-m-d H:i:s', 1394726400);exit;
		echo $datetime;exit;
		
		$startTime = $datetime-86400*30;
		$endTime   = $datetime-86400*30+86399;
		$uStartTime = $datetime-86400*7;
		$uEndTime   = $datetime-1+86400;
		
		$startTimes = $datetime;
		$endTimes   = $datetime+86399;
		
		$query = array(
				'tbPushAppOperaInfo.aoAppendTime' => array(
						'$gte' => $startTime,
						'$lte' => $endTime
				),
				//'pcAppID' => 'app5'
		);
		$query = array(
				//'pcAppendTime' => array(
				//				'$gte' => $startTimes,
				//				'$lte' => $endTimes
				//		),
// 				'tbPushAppOperaInfo.aoAppendTime' => array(
// 						'$gte' => $uStartTime,
// 						'$lte' => $uEndTime
// 				)
				//'pcAppID' => 'app5'
				'PushClientID' => '469794',
		);
// 		ComFun::pr($query);
		echo date('Y-m-d H:i:s', $query['pcAppendTime']['$gte']) . '<br>';
		echo date('Y-m-d H:i:s', $query['pcAppendTime']['$lte']) . '<br>';
	//	echo date('Y-m-d H:i:s', $query['tbPushAppOperaInfo.aoAppendTime']['$gte']) . '<br>';
	//	echo date('Y-m-d H:i:s', $query['tbPushAppOperaInfo.aoAppendTime']['$lte']) . '<br>';
		
		$field = array(
					'pcAppendTime' => 1,
					'tbPushAppOperaInfo.aoAppendTime' => 1
				);
		//->field($field)->cond($query)
		$_re = $this->mongo_model->table('testPushClientDataAna')->findOne();
		
		echo date('Y-m-d H:i:s', '1387185632') . '<br>';
		echo date('Y-m-d H:i:s', '1389789706') . '<br>';
		echo '<br>';
		
		count($_re);
		ComFun::pr($_re);
		exit;
		if ( $_re ) {
			foreach ( $_re as $_v ) {
				$_count = 0;
				ComFun::pr($_v['tbPushAppOperaInfo']);
				if ( $_v['tbPushAppOperaInfo'] ) {
					foreach ( $_v['tbPushAppOperaInfo'] as $_v_2 ) {
						if ( $_v_2['aoAppendTime'] >= $startTime && $_v_2['aoAppendTime '] <= $endTime ) {
							$_count++;
							echo date('Y-m-d H:i:s', $_v_2['aoAppendTime']) . '<br>';
						}
					}
				}
				echo $_count . '<br>';
			}
		}
	}
	
	public function iframe () {
		exit;
		
		//Z2laZ1JKUXBsNlRuem16QUd1UzZrUT09
		$tArr['access_token'] = 'SW8wVWZGR3RZdWw0c3l4TTFFbFRjZTdHT2FnVEdwajg%3D';
		$tArr['client_id'] = 'app24';
		$tArr['analysis_time'] = strtotime('2013-09-25 09:14:20');
		$tArr['width']     = 800;
		$tArr['height']    = 300;
		
		$url = '/chart/user_new?' . http_build_query($tArr);
		
		$this->redirect( $url );
		echo $url;
	}
	
	/**
	 * soap
	 */
	public function soap () {
		//exit;
		//$data = "{\"Status\":\"1\",\"AutoID\":\"2\"}";
		
		//echo $data;exit;
		
		//exit;
		$tableName = 'getAnaShortUrlPV';
		$type = 'auth';
		$type = 'self';
		$condition = '';
		$DBSoap = new DBSoap();
		
		echo '==========table=========<br>';
		echo $tableName;
		
		echo '<br>=========insert=========<br>';
		// 		$idata['aName'] = '报刊杂志';
		// 		$ire = $DBSoap->InsertTableInfo($type, 'Insert'.$tableName, $idata);
		// 		ComFun::pr($ire);
		// 		exit;
		
		echo '<br>=========update=========<br>';
		// 		$udata['AutoID'] = 5;
		// 		$udata['Status'] = 2;
		// 		$ure = $DBSoap->UpdateTableInfo($type, 'Update'.$tableName, $udata);
		// 		ComFun::pr($ure);
		// 		exit;
		
		echo '<br>=========Delete=========<br>';
		// 		$ddata['AutoID'] = 1;
		// 		$ure = $DBSoap->DeleteTableInfo($type, 'Delete'.$tableName, $ddata);
		// 		ComFun::pr($ure);
		// 		exit;
		
		echo '<br>==========select==========<br>';
// 				$where = "imName = \"QQ\"";
		
// 				$cond['UserID'] = 'aVB2bXpudC9yOWs9';
// 				$cond['client_id'] = '80002001';
// 		$sre = $DBSoap->SelectTableInfo($type, 'Select'.$tableName, $cond);
// 		ComFun::pr($sre);
		
// 				exit;
		
		echo '<br>==========list==========<br>';
		// 		$tableName = 'App';
		// 		$tableName = 'UserKey';
		// 		$type = 'dev';
		// 		$page=1;
		// 		$pagesize=20;
// 		 		$where = 'pStatues=0';
// 		$type = 'expand';
// 		$tableName = 'AppPlugInInfo';
// 		$lre = $DBSoap->GetTableList($type, 'Get'.$tableName.'List', $page, $pagesize, $where);
		
// 		ComFun::pr($lre);
// 		exit;
		
		echo '<br>==========get==========<br>';
		$type = 'analysis';
		$tArr['data_interval'] = 30;
		$tArr['anDateTime'] = mktime(0,0,0,'04','18','2014');
		$_cond['paAppID'] = 'app5';
		$gre = $DBSoap->GetTableInfo($type, 'GetAnaShortUrlKeyword', $tArr);
		ComFun::pr($gre);
		echo date('Y-m-d H:i:s', '1386080923');
		echo '<br>';
		echo date('Y-m-d H:i:s', '1386656923');
		echo '<br>';
		echo date('Y-m-d H:i:s', '1386302038');
	}
	public function phone(){
		exit;
		$html = '<form method="get" action="/test/phone">';
		$html .= '系统批号：<input type="text" name="poSystemName" />';
		$html .= '系统名称：<input type="text" name="poPhoneName" />';
		$html .= '<input type="submit" value="提交" />';
		$html .= '</form>';
	
		echo $html;
	
		if( $_GET['poSystemName'] && $_GET['poPhoneName'] ){
			$tArr['poSystemName'] = trim($_GET['poSystemName']);
			$tArr['poPhoneName'] = trim($_GET['poPhoneName']);
	
			$dbPhoneOS = $this->getClass('DB_PhoneOS');
			$dbPhoneOS->addResult($tArr);
				
			ComFun::pr($dbPhoneOS->getResult());
		}
	}
	public function upfile () {
		exit;
		$this->display('report/upfile.html');
	}
}