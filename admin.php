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
 * @category plugins
 * @package banner
 * @copyright  MAGIX CMS Copyright (c) 2011 - 2013 Gerits Aurelien, http://www.magix-dev.be, http://www.magix-cms.com
 * @license Dual licensed under the MIT or GPL Version 3 licenses.
 * @version 2.0
 * @create 26-08-2011
 * @Update 12-04-2021
 * @author Gérits Aurélien <contact@magix-dev.be>
 * @author Salvatore Di Salvo <disalvo.infographiste@gmail.com>
 * @name plugins_banner_admin
 */
class plugins_banner_admin extends plugins_banner_db
{
    /**
     * @var object
     */
    protected
        $controller,
        $message,
        $template,
        $plugins,
        $modelLanguage,
        $collectionLanguage,
        $data,
        $header,
        $upload,
        $imagesComponent,
        $routingUrl,
        $finder,
        $makeFiles;

	/**
	 * @var string
	 */
	public
        $getlang,
        $action,
        $tab,
        $img;

	/**
	 * @var int
	 */
	public
        $edit,
        $id;

	/**
	 * @var array
	 */
	public
        $banner = [];

    /**
     * plugins_banner_admin constructor.
     */
	public function __construct()
    {
		$this->template = new backend_model_template();
		$this->plugins = new backend_controller_plugins();
		$this->message = new component_core_message($this->template);
		$this->modelLanguage = new backend_model_language($this->template);
		$this->collectionLanguage = new component_collections_language();
		$this->data = new backend_model_data($this);
		$this->header = new http_header();
		$this->upload = new component_files_upload();
		$this->imagesComponent = new component_files_images($this->template);
		$this->routingUrl = new component_routing_url();
        $this->finder = new file_finder();
        $this->makeFiles = new filesystem_makefile();
		$formClean = new form_inputEscape();

		// --- Get
		if (http_request::isGet('controller')) $this->controller = $formClean->simpleClean($_GET['controller']);
        if($this->controller === 'banner') $this->controller = 'home';
		if (http_request::isGet('edit')) $this->edit = $formClean->numeric($_GET['edit']);
		if (http_request::isRequest('action')) $this->action = $formClean->simpleClean($_REQUEST['action']);
		if (http_request::isGet('tabs')) $this->tab = $formClean->simpleClean($_GET['tabs']);

		// --- Post
		if (http_request::isPost('banner')) $this->banner = $formClean->arrayClean($_POST['banner']);
		if (http_request::isPost('id')) $this->id = $formClean->simpleClean($_POST['id']);
		// --- Image Upload
		if (isset($_FILES['img']["name"])) $this->img = http_url::clean($_FILES['img']["name"]);
		// --- Order
		if (http_request::isPost('banner')) $this->banner = $formClean->arrayClean($_POST['banner']);
	}

	/**
	 * Method to override the name of the plugin in the admin menu
	 * @return string
	 */
	public function getExtensionName(): string
	{
		return $this->template->getConfigVars('banner_plugin');
	}

	// --- Database actions

	/**
	 * Assign data to the defined variable or return the data
	 * @param string $type
	 * @param string|int|null $id
	 * @param string $context
	 * @param boolean $assign
	 * @return false|null|array
	 */
	protected function getItems(string $type, $id = null, $context = null, $assign = true)
    {
		return $this->data->getItems($type, $id, $context, $assign);
	}

    /**
     * Insert data
     * @param array $config
     */
    protected function add(array $config)
    {
        switch ($config['type']) {
            case 'banner':
            case 'bannerContent':
                parent::insert(
                    ['type' => $config['type']],
                    $config['data']
                );
                break;
        }
    }

    /**
     * Update data
     * @param array $config
     */
    protected function upd(array $config)
    {
        switch ($config['type']) {
            case 'img':
            case 'banner':
            case 'bannerContent':
            case 'order':
                parent::update(
                    ['type' => $config['type']],
                    $config['data']
                );
                break;
        }
    }

    /**
     * Delete a record
     * @param array $config
     */
    protected function del(array $config)
    {
        switch ($config['type']) {
            case 'banner':
                parent::delete(
                    ['type' => $config['type']],
                    $config['data']
                );
                $this->message->json_post_response(true,'delete',array('id' => $this->id));
                break;
        }
    }

    // ---

    /**
     * @param $type
     */
    protected function order($type){
        switch ($type) {
            case 'home':
                for ($i = 0; $i < count($this->banner); $i++) {
                    $this->upd(['type' => 'order', 'data' => ['id_banner' => $this->banner[$i], 'order_banner' => $i]]);
                }
                break;
        }
    }

	/**
	 * Create and insert the address image
	 * @param string $img
	 * @param string $name
	 * @param int $id
	 * @param bool $debug
	 * @return null|string|array
	 */
	protected function insert_image(string $img, string $name, int $id, $debug = false)
    {
		if(isset($this->$img)) {
			$resultUpload = $this->upload->setImageUpload(
				'img',
				[
                    'name'            => filter_rsa::randMicroUI(),
                    'edit'            => $name,
                    'prefix'          => ['s_','m_','l_'],
                    'module_img'      => 'banner',
                    'attribute_img'   => 'banner',
                    'original_remove' => false
                ], [
                    'upload_root_dir' => 'upload/banner', //string
                    'upload_dir'      => $id //string ou array
                ],
				$debug
			);

			$this->upd([
                'type' => 'img',
                'data' => [
                    'id_banner' => $id,
                    'img_banner' => $resultUpload['file']
                ]
            ]);

			return $resultUpload;
		}
		return null;
	}

	/**
	 * @param string|int $id
	 * @return bool
	 */
	protected function delete_image($id): bool
	{
        $setImgDirectory = $this->routingUrl->dirUpload('upload/banner/'.$id, true);

        if (file_exists($setImgDirectory)) {
            $setFiles = $this->finder->scanDir($setImgDirectory);
            $clean = '';
            if ($setFiles != null) {
                foreach ($setFiles as $file) {
                    $clean .= $this->makeFiles->remove($setImgDirectory . $file);
                }
            }
            $this->makeFiles->remove($setImgDirectory);
            return true;
        }
        else {
            return false;
        }
	}

	/**
	 * @param array $data
	 * @return array
	 */
	protected function setItembannerData(array $data): array
	{
		$arr = [];
		if(!empty($data)) {
            foreach ($data as $banner) {
                if (!array_key_exists($banner['id_banner'], $arr)) {
                    $arr[$banner['id_banner']] = [];
                    $arr[$banner['id_banner']]['id_banner'] = $banner['id_banner'];
                    $arr[$banner['id_banner']]['img_banner'] = $banner['img_banner'];
                    $imgPrefix = $this->imagesComponent->prefix();
                    $fetchConfig = $this->imagesComponent->getConfigItems([
                        'module_img'    =>'banner',
                        'attribute_img' =>'banner'
                    ]);
                    foreach ($fetchConfig as $key => $value) {
                        $arr[$banner['id_banner']]['imgSrc'][$value['type_img']] = '/upload/banner/'.$banner['id_banner'].'/'.$imgPrefix[$value['type_img']] . $banner['img_banner'];
                    }
                }

                $arr[$banner['id_banner']]['content'][$banner['id_lang']] = [
                    'id_lang' => $banner['id_lang'],
                    'title_banner' => $banner['title_banner'],
                    'desc_banner' => $banner['desc_banner'],
                    'url_banner' => $banner['url_banner'],
                    'blank_banner' => $banner['blank_banner'],
                    'published_banner' => $banner['published_banner']
                ];
            }
        }
		return $arr;
	}

    /**
     * Adds the plugin in resizing images
     * @return array
     */
    public function getItemsImages(): array
    {
        $data = $this->getItems('img',NULL,'all',false);
        $newArr = [];
        if(!empty($data)) {
            foreach($data as $key => $value){
                $newArr[$key]['id'] = $value['id_banner'];
                $newArr[$key]['img'] = $value['img_banner'];
            }
        }
        return $newArr;
    }

	/**
	 * @access public
	 */
	/*public function run() {
		if(isset($this->action)) {
            switch ($this->action) {
                case 'add':
                case 'edit':
                    if( isset($this->banner) && !empty($this->banner) ) {
                        $notify = 'update';

                        if (!isset($this->banner['id'])) {
                            $this->add([
                                'type' => 'banner',
                                'data' => [
                                    'module' => $this->controller,
                                    'id_module' => NULL
                                ]
                            ]);

                            $lastbanner = $this->getItems('lastbanner', null,'one',false);
                            $this->banner['id'] = $lastbanner['id_banner'];
                            $notify = 'add_redirect';
                        }

                        if(isset($this->img) && !empty($this->img)) {
                            if(isset($this->banner['id']) && !empty($this->banner['id'])) {
                                $setImgDirectory = $this->routingUrl->dirUpload('upload/banner/' . $this->banner['id'],true);

                                if (file_exists($setImgDirectory)) {
                                    $setFiles = $this->finder->scanDir($setImgDirectory);
                                    $clean = '';
                                    if ($setFiles != null) {
                                        foreach ($setFiles as $file) {
                                            $this->makeFiles->remove($setImgDirectory . $file);
                                        }
                                    }
                                }
                            }

                            $img = $this->insert_image('img', '', $this->banner['id'], false);
                            $img = $img['file'];

                            $this->upd([
                                'type' => 'img',
                                'data' => [
                                    'id' => $this->banner['id'],
                                    'img' => $img
                                ]
                            ]);
                        }

                        foreach ($this->banner['content'] as $lang => $banner) {
                            $banner['id_lang'] = $lang;
                            $banner['blank_banner'] = (!isset($banner['blank_banner']) ? 0 : 1);
                            $banner['published_banner'] = (!isset($banner['published_banner']) ? 0 : 1);
                            $bannerLang = $this->getItems('bannerContent',['id' => $this->banner['id'],'id_lang' => $lang],'one',false);

                            if($bannerLang) $banner['id'] = $bannerLang['id_banner_content'];
                            else $banner['id_banner'] = $this->banner['id'];

                            $config = ['type' => 'bannerContent', 'data' => $banner];

                            $bannerLang ? $this->upd($config) : $this->add($config);
                        }
                        $this->message->json_post_response(true,$notify);
                    }
                    else {
                        $this->modelLanguage->getLanguage();

                        if(isset($this->edit)) {
                            $collection = $this->getItems('bannerContent',$this->edit,'all',false);
                            $setEditData = $this->setItembannerData($collection);
                            $this->template->assign('banner', $setEditData[$this->edit]);
                        }

                        $this->template->assign('edit',$this->action === 'edit');
                        $this->template->display('edit.tpl');
                    }
                    break;
                case 'delete':
                    if(isset($this->id) && !empty($this->id)) {
                        if($this->delete_image($this->id)) {
                            $this->del([
                                'type' => 'banner',
                                'data' => ['id' => $this->id]
                            ]);
                        }
                    }
                    break;
                case 'order':
                    if (isset($this->banner) && is_array($this->banner)) {
                        $this->order('home');
                    }
                    break;
            }
		}
		else {
			$this->modelLanguage->getLanguage();
			$defaultLanguage = $this->collectionLanguage->fetchData(['context'=>'one','type'=>'default']);
			$this->getItems('banners',['lang' => $defaultLanguage['id_lang'], 'module' => $this->controller, 'id_module' => null],'all');
			$assign = [
                'id_banner',
                'url_banner' => ['title' => 'name'],
                'img_banner' => ['type' => 'bin', 'input' => null, 'class' => ''],
                'title_banner' => ['title' => 'name'],
                'desc_banner' => ['title' => 'name']
            ];
			$this->data->getScheme(['mc_banner', 'mc_banner_content'], ['id_banner', 'url_banner', 'img_banner','title_banner','desc_banner'], $assign);
			$this->template->display('index.tpl');
		}
	}*/
}