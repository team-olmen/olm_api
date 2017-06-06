DROP TABLE IF EXISTS `tmp_users`;
CREATE TABLE `tmp_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(100) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

INSERT INTO `tmp_users` (
  `email`
 )
 SELECT
  `t1`.`item_title` AS `email`
 FROM `db10642994-olmen4`.`edt_eddy_item` AS `t1`
 LEFT JOIN `db10642994-olmen4`.`edt_eddy_user` AS `t2`
 ON `t1`.`item_id` = `t2`.`item_id`
 WHERE
  `t1`.`item_status` = 'current' AND
  `t1`.`item_type` = 'Eddy::User' AND
  `t2`.`user_last_login` > 1451606400
;

INSERT INTO `olm_users` (
  `username`,
  `email`,
  `password`,
  `salt`,
  `enabled`,
  `account_non_expired`,
  `credentials_non_expired`,
  `account_non_locked`,
  `roles`
 )
 SELECT
  `email`,
  `email`,
  '$2y$13$iYODQBn6rIBfFZ80qfBTIe6qOagqFTbC0vcnB42fahux/0JIEa1Oe',
  '',
  0,
  1,
  1,
  1,
  'ROLE_USER'
 FROM `tmp_users`
;
