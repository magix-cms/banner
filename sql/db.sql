CREATE TABLE IF NOT EXISTS `mc_banner` (
  `id_banner` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `img_banner` varchar(25) NOT NULL,
  `module_banner` varchar(25) NOT NULL DEFAULT 'home',
  `id_module` int(11) DEFAULT NULL,
  `order_banner` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`id_banner`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `mc_banner_content` (
  `id_banner_content` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `id_banner` smallint(5) unsigned NOT NULL,
  `id_lang` smallint(3) unsigned NOT NULL,
  `url_banner` varchar(125) NOT NULL,
  `blank_banner` smallint(1) unsigned NOT NULL default 0,
  `title_banner` varchar(125) NOT NULL,
  `desc_banner` text,
  `published_banner` smallint(1) unsigned NOT NULL default 0,
  PRIMARY KEY (`id_banner_content`),
  KEY `id_lang` (`id_lang`),
  KEY `id_banner` (`id_banner`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

ALTER TABLE `mc_banner_content`
  ADD CONSTRAINT `mc_banner_content_ibfk_1` FOREIGN KEY (`id_banner`) REFERENCES `mc_banner` (`id_banner`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `mc_banner_content_ibfk_2` FOREIGN KEY (`id_lang`) REFERENCES `mc_lang` (`id_lang`) ON DELETE CASCADE ON UPDATE CASCADE;

INSERT INTO `mc_config_img` (`id_config_img`, `module_img`, `attribute_img`, `width_img`, `height_img`, `type_img`, `resize_img`) VALUES
  (null, 'plugins', 'banner', '480', '192', 'small', 'adaptive'),
  (null, 'plugins', 'banner', '960', '384', 'medium', 'adaptive'),
  (null, 'plugins', 'banner', '1920', '768', 'large', 'adaptive');

INSERT INTO `mc_admin_access` (`id_role`, `id_module`, `view`, `append`, `edit`, `del`, `action`)
  SELECT 1, m.id_module, 1, 1, 1, 1, 1 FROM mc_module as m WHERE name = 'banner';