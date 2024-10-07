<?php
require_once ('db.php');
/*
 # -- BEGIN LICENSE BLOCK ----------------------------------
 #
 # This file is part of MAGIX CMS.
 # MAGIX CMS, The content management system optimized for users
 # Copyright (C) 2008 - 2021 magix-cms.com <support@magix-cms.com>
 #
 # OFFICIAL TEAM :
 #
 #   * Gerits Aurelien (Author - Developer) <aurelien@magix-cms.com> <contact@aurelien-gerits.be>
 #
 # Redistributions of files must retain the above copyright notice.
 # This program is free software: you can redistribute it and/or modify
 # it under the terms of the GNU General Public License as published by
 # the Free Software Foundation, either version 3 of the License, or
 # (at your option) any later version.
 #
 # This program is distributed in the hope that it will be useful,
 # but WITHOUT ANY WARRANTY; without even the implied warranty of
 # MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 # GNU General Public License for more details.
 #
 # You should have received a copy of the GNU General Public License
 # along with this program.  If not, see <http://www.gnu.org/licenses/>.
 #
 # -- END LICENSE BLOCK -----------------------------------
 #
 # DISCLAIMER
 #
 # Do not edit or add to this file if you wish to upgrade MAGIX CMS to newer
 # versions in the future. If you wish to customize MAGIX CMS for your
 # needs please refer to http://www.magix-cms.com for more information.
 */
/**
 * @category plugin
 * @package banner
 * @copyright MAGIX CMS Copyright (c) 2011 Gerits Aurelien, http://www.magix-dev.be, http://www.magix-cms.com
 * @license Dual licensed under the MIT or GPL Version 3 licenses.
 * @version 2.0
 * @create 26-08-2011
 * @Update 12-04-2021
 * @author Aurélien Gérits <aurelien@magix-cms.com>
 * @author Salvatore Di Salvo <disalvo.infographiste@gmail.com>
 * @name plugins_banner_public
 */
class plugins_banner_public extends plugins_banner_db
{
    /**
     * @var object
     */
	protected
        $template,
        $data,
        $imagesComponent;

    /**
     * @var string
     */
	protected
        $lang;

    /**
     * plugins_banner_public constructor.
     * @param frontend_model_template|null $t
     */
	public function __construct($t = null)
    {
		$this->template = $t instanceof frontend_model_template ? $t : new frontend_model_template();
        $this->data = new frontend_model_data($this,$this->template);
		$this->lang = $this->template->lang;
	}

	/**
	 * Assign data to the defined variable or return the data
	 * @param string $type
	 * @param string|int|null $id
	 * @param string $context
	 * @param boolean $assign
	 * @return false|null|array
	 */
	private function getItems(string $type, $id = null, $context = null, $assign = true)
    {
		return $this->data->getItems($type, $id, $context, $assign);
	}

	/**
	 * @return void
	 */
	private function initImageComponent(): void {
		if(!isset($this->imagesComponent)) $this->imagesComponent = new component_files_images($this->template);
	}

	/**
	 * @param array $data
	 * @return array
	 */
	private function setItemBannerData(array $data): array
	{
		$arr = [];
        $extwebp = 'webp';
        if(!empty($data)) {
			$this->initImageComponent();

            foreach ($data as $banner) {
                $arr[$banner['id_banner']] = [
					'id_banner' => $banner['id_banner'],
					'id_lang' => $banner['id_lang'],
					'title_banner' => $banner['title_banner'],
					'desc_banner' => $banner['desc_banner'],
					'url_banner' => $banner['url_banner'],
					'blank_banner' => $banner['blank_banner'],
					'img' => $this->imagesComponent->setModuleImage('banner','banner',$banner['img_banner'],$banner['id_banner'])
				];
            }
        }
		return $arr;
	}

    /**
     * @param array $params
     * @return array
     */
	public function getBanners(array $params = []): array
	{
        $banners = $this->getItems('activebanners',['module_banner' => $params['controller'] ?? 'home','id_module' => $params['id_module'] ?? NULL,'lang' => $this->lang],'all', false);
        if(empty($banners) && isset($params['root'])){
            $banners = $this->getItems('activebanners', ['module_banner' => $params['root'] ?? 'home', NULL, 'lang' => $this->lang], 'all', false);
        }
        return $this->setItemBannerData($banners);
	}
}