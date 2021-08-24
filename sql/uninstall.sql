TRUNCATE TABLE `mc_banner_content`;
DROP TABLE `mc_banner_content`;
TRUNCATE TABLE `mc_banner`;
DROP TABLE `mc_banner`;

DELETE FROM `mc_config_img` WHERE `module_img` = 'plugins' AND `attribute_img` = 'banner';

DELETE FROM `mc_admin_access` WHERE `id_module` IN (
    SELECT `id_module` FROM `mc_module` as m WHERE m.name = 'banner'
);