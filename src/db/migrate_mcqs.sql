DROP TABLE IF EXISTS `tmp_module`;
CREATE TABLE `tmp_module` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_bin NOT NULL,
  `code` varchar(4) COLLATE utf8_bin NOT NULL,
  `item` varchar(36) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

INSERT INTO `tmp_module` (
  `name`,
  `code`,
  `item`
 )
 SELECT
  `t1`.`item_title` AS `name`,
  CONCAT('M', `t2`.`module_number`) AS `code`,
  `t1`.`item_id` AS `item`
 FROM `db10642994-olmen4`.`edt_eddy_item` AS `t1`
 LEFT JOIN `db10642994-olmen4`.`edt_olm_module` AS `t2`
 ON `t1`.`item_id` = `t2`.`item_id`
 WHERE
  `t1`.`item_status` = 'current' AND
  `t1`.`item_type` = 'OLM::Module'
;

INSERT INTO `olm_modules` (
  `id`,
  `name`,
  `code`
 )
 SELECT
  `id`,
  `name`,
  `code`
 FROM `tmp_module`
;

INSERT INTO `olm_modules_history` (
  `history_user`,
  `history_status`,
  `history_timestamp`,
  `id`,
  `name`,
  `code`
 )
 SELECT
  1,
  'created',
  NOW(),
  `id`,
  `name`,
  `code`
 FROM `olm_modules`
;


DROP TABLE IF EXISTS `tmp_mcq`;
CREATE  TABLE  `tmp_mcq` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `module` int(11) NOT NULL,
 `rating` int(11) NOT NULL,
 `original` tinyint(1) NOT NULL,
 `complete` tinyint(1) NOT NULL,
 `generation` int(11) NOT NULL,
 `version` int(11) NOT NULL,
 `q` varchar(10000) COLLATE utf8_bin NOT  NULL ,
 `a1` varchar(1000) COLLATE utf8_bin NOT  NULL ,
 `a2` varchar(1000) COLLATE utf8_bin  NOT  NULL ,
 `a3` varchar(1000) COLLATE utf8_bin  NOT  NULL ,
 `a4` varchar(1000) COLLATE utf8_bin  NOT  NULL ,
 `a5` varchar(1000) COLLATE utf8_bin  NOT  NULL ,
 `a6` varchar(1000) COLLATE utf8_bin  NOT  NULL ,
 `a7` varchar(1000) COLLATE utf8_bin  NOT  NULL ,
 `a8` varchar(1000) COLLATE utf8_bin  NOT  NULL ,
 `a9` varchar(1000) COLLATE utf8_bin  NOT  NULL ,
 `a10` varchar(1000) COLLATE utf8_bin  NOT  NULL ,
 `s` int(11)  NOT  NULL ,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


TRUNCATE TABLE `tmp_mcq`;
INSERT INTO `tmp_mcq` (
 `module`,
 `rating`,
 `original`,
 `complete`,
 `generation`,
 `version`,
 `q`,
 `a1`,
 `a2`,
 `a3`,
 `a4`,
 `a5`,
 `a6`,
 `a7`,
 `a8`,
 `a9`,
 `a10`,
 `s`
 )
 SELECT
  `t3`.`id` AS `module`,
  0 AS `rating`,
  `t2`.`mcq_is_original` AS `original`,
  `t2`.`mcq_is_complete` AS `complete`,
  2 AS `generation`,
  `t1`.`item_version` AS `version`,
  `t1`.`item_title` AS `q`,
  `t2`.`mcq_answer1` AS `a1`,
  `t2`.`mcq_answer2` AS `a2`,
  `t2`.`mcq_answer3` AS `a3`,
  `t2`.`mcq_answer4` AS `a4`,
  `t2`.`mcq_answer5` AS `a5`,
  `t2`.`mcq_answer6` AS `a6`,
  `t2`.`mcq_answer7` AS `a7`,
  `t2`.`mcq_answer8` AS `a8`,
  `t2`.`mcq_answer9` AS `a9`,
  `t2`.`mcq_answer10` AS `a10`,
  `t2`.`mcq_solution` AS `s`
 FROM `db10642994-olmen4`.`edt_eddy_item` AS `t1`
 LEFT JOIN `db10642994-olmen4`.`edt_olm_mcq` AS `t2`
 ON `t1`.`item_id` = `t2`.`item_id`
 LEFT JOIN `tmp_module` AS `t3`
 ON `t2`.`module_id` = `t3`.`item`
 WHERE
  `t1`.`item_status` = 'current' AND
  `t1`.`item_type` = 'OLM::MCQ'
;

DELETE FROM `olm_modules`
 WHERE `id` IN (
  SELECT `tmp_module`.`id`
  FROM `tmp_module`
  LEFT JOIN `tmp_mcq` ON `tmp_module`.`id` = `tmp_mcq`.`module`
  GROUP BY `tmp_module`.`id`
  HAVING COUNT( `tmp_mcq`.`module` ) = 0
);

