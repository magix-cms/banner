TRUNCATE TABLE `mc_banner_content`;
DROP TABLE `mc_banner_content`;
TRUNCATE TABLE `mc_banner`;
DROP TABLE `mc_banner`;

DELETE FROM `mc_config_img` WHERE `module_img` = 'banner' AND `attribute_img` = 'banner';