<?php 

class LMS_db {

	private $db;

	public function __construct(){
		global $wpdb;
		$this->db=$wpdb;
	}

	public function create_db(){
		$query=$this->db->query("CREATE TABLE IF NOT EXISTS `".$this->db->prefix."lms_groups` (
															  `group_id` int(11) NOT NULL AUTO_INCREMENT,
															  `name` varchar(255) NOT NULL,
															  `view_limit` int(11) NOT NULL,
															  `text_id` varchar(255) NOT NULL,
															  `description` text NOT NULL,
															  `user_id` int(11) NOT NULL,
															  `notice` text CHARACTER SET utf8 NOT NULL,
															  `video_demand` int(11) NOT NULL,
															  `video_review` int(11) NOT NULL,
															  `group_test_view` int(11) NOT NULL,
															  `remove` varchar(100) CHARACTER SET utf8 NOT NULL,
															  PRIMARY KEY (`group_id`)
															) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;"
								);


		$query=$this->db->query("CREATE TABLE IF NOT EXISTS `".$this->db->prefix."lms_group_courses` (
															  `id` int(11) NOT NULL AUTO_INCREMENT,
															  `group_id` int(11) NOT NULL,
															  `course_id` int(11) NOT NULL,
															  PRIMARY KEY (`id`)
															) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;"
								);


		$query=$this->db->query("CREATE TABLE IF NOT EXISTS `".$this->db->prefix."lms_group_tests` (
															  `group_test_id` int(11) NOT NULL AUTO_INCREMENT,
															  `group_id` int(11) NOT NULL,
															  `test_id` int(11) NOT NULL,
															  `view_limit` int(11) NOT NULL,
															  PRIMARY KEY (`group_test_id`)
															) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;"
								);


		$query=$this->db->query("CREATE TABLE IF NOT EXISTS `".$this->db->prefix."lms_group_users` (
															  `group_user_id` int(11) NOT NULL AUTO_INCREMENT,
															  `group_id` int(11) NOT NULL,
															  `user_id` int(11) NOT NULL,
															  `user_level` int(11) NOT NULL,
															  PRIMARY KEY (`group_user_id`)
															) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;"
								);


		$query=$this->db->query("CREATE TABLE IF NOT EXISTS `".$this->db->prefix."lms_hubs` (
															  `hub_id` int(11) NOT NULL AUTO_INCREMENT,
															  `name` varchar(255) NOT NULL,
															  `url` varchar(255) NOT NULL,
															  `key` varchar(255) NOT NULL,
															  PRIMARY KEY (`hub_id`)
															) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;");
		$query=$this->db->query("CREATE TABLE IF NOT EXISTS `".$this->db->prefix."lms_orders` (
															  `order_id` int(11) NOT NULL AUTO_INCREMENT,
															  `test_id` int(11) NOT NULL,
															  `user_id` int(11) NOT NULL,
															  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
															  `processed` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
															  `status` int(11) NOT NULL,
															  PRIMARY KEY (`order_id`)
															) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;");


		$query=$this->db->query("CREATE TABLE IF NOT EXISTS `".$this->db->prefix."lms_questions` (
															  `question_id` int(11) NOT NULL AUTO_INCREMENT,
															  `test_id` int(11) NOT NULL,
															  `type` varchar(16) NOT NULL,
															  `options` text NOT NULL,
															  `num` int(11) NOT NULL,
															  `title` varchar(255) NOT NULL,
															  `answer` int(11) NOT NULL,
															  PRIMARY KEY (`question_id`)
															) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;");


		$query=$this->db->query("CREATE TABLE IF NOT EXISTS `".$this->db->prefix."lms_test_hits` (
															  `test_hit_id` int(11) NOT NULL AUTO_INCREMENT,
															  `test_id` int(11) NOT NULL,
															  `user_id` int(11) NOT NULL,
															  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
															  PRIMARY KEY (`test_hit_id`)
															) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5231 ;");

	}
}


$GLOBALS['LMS_db']=new LMS_db();
register_activation_hook( __FILE__, array($GLOBALS['LMS_db'], 'create_db');