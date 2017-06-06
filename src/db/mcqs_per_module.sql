SELECT `tmp_module`.`id`, `tmp_module`.`name`, COUNT( `tmp_mcq`.`module` )
 FROM `tmp_module`
 LEFT JOIN `tmp_mcq` ON `tmp_module`.`id` = `tmp_mcq`.`module`
 GROUP BY `tmp_module`.`id`;
