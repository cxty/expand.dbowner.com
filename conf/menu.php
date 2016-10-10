<?php 
return array(
		array(
				'top' => array(
						'title' => 'index',
						'state' => Lang::get('Index')
				),
				'left' => array(
						array(
								'title' => 'index',
								'state' => Lang::get('Introduce'),
								'href' => '/admin/introduce',
						)
				)
		),
		array(
				'top' => array(
						'title' => 'permission',
						'state' => Lang::get('AppPerm')
				),
				'left' => array(
						array(
								'title' => 'permission',
								'state' => Lang::get('AppPermList'),
								'href' => '/admin/appPerm',
						),
						array(
								'title' => 'appPlus',
								'state' => Lang::get('AppPlusList'),
								'href' => '/admin/appPlus',
						)
				)
		),
		array(
				'top' => array(
						'title' => 'appPluss',
						'state' => Lang::get('AppPlus')
				),
				'left' => array(
						array(
								'title' => 'appPluss',
								'state' => Lang::get('AppPlusList'),
								'href' => '/admin/appPlus',
						),
						array(
								'title' => 'appPlusUser',
								'state' => Lang::get('AppPlusUser'),
								'href' => '/admin/appPlus',
						),
						array(
								'title' => 'appPlusApp',
								'state' => Lang::get('AppPlusApp'),
								'href' => '/admin/appPlus',
						)
				)
		),
);
?>