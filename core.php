<?php
/**
 * Class plugins_test_core
 * Fichier pour les plugins core
 */
class plugins_banner_core extends plugins_banner_admin
{
    /**
     * @var object
     */
    protected
        $modelPlugins,
        $plugins;

    /**
     * @var int
     */
    public
        $mod_edit;

    /**
     * @var string
     */
    public
        $mod_action,
        $plugin;

    /**
     * plugins_banner_core constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->modelPlugins = new backend_model_plugins();
        $this->plugins = new backend_controller_plugins();
        $formClean = new form_inputEscape();

        if (http_request::isGet('plugin')) $this->plugin = $formClean->simpleClean($_GET['plugin']);
        if (http_request::isRequest('mod_action')) $this->mod_action = $formClean->simpleClean($_REQUEST['mod_action']);
        if (http_request::isGet('mod_edit')) $this->mod_edit = $formClean->numeric($_GET['mod_edit']);
    }

    /**
     *
     */
    protected function runAction()
    {
        switch ($this->mod_action) {
            case 'add':
            case 'edit':
                if( isset($this->banner) && !empty($this->banner) ) {
                    $notify = 'update';
                    $img = '';

                    if (!isset($this->banner['id'])) {
                        $this->add([
                            'type' => 'banner',
                            'data' => [
                                'module' => $this->controller,
                                'id_module' => $this->edit ?: NULL
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
                                        $clean .= $this->makeFiles->remove($setImgDirectory . $file);
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

                    if(isset($this->mod_edit)) {
                        $collection = $this->getItems('bannerContent',$this->mod_edit,'all',false);
                        $setEditData = $this->setItembannerData($collection);
                        $this->template->assign('banner', $setEditData[$this->mod_edit]);
                    }

                    $this->template->assign('edit',$this->mod_action === 'edit');
                    $this->modelPlugins->display('mod/edit.tpl');
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

    /**
     *
     */
    protected function adminList()
    {
        $this->modelLanguage->getLanguage();
        $defaultLanguage = $this->collectionLanguage->fetchData(['context'=>'one','type'=>'default']);
        $this->getItems('banners',['lang' => $defaultLanguage['id_lang'], 'module' => $this->controller, 'id_module' => $this->edit ?: NULL],'all');
        $assign = [
            'id_banner',
            'url_banner' => ['title' => 'name'],
            'img_banner' => ['type' => 'bin', 'input' => null, 'class' => ''],
            'title_banner' => ['title' => 'name'],
            'desc_banner' => ['title' => 'name']
        ];
        $this->data->getScheme(['mc_banner', 'mc_banner_content'], ['id_banner', 'url_banner', 'img_banner','title_banner','desc_banner'], $assign);
        $this->modelPlugins->display('mod/index.tpl');
    }

    /**
     * Execution du plugin dans un ou plusieurs modules core
     */
    public function run() {
        if(isset($this->controller)) {
            switch ($this->controller) {
                case 'about':
                    $extends = $this->controller.(!isset($this->action) ? '/index.tpl' : '/pages/edit.tpl');
                    break;
                case 'category':
                case 'product':
                    $extends = 'catalog/'.$this->controller.'/edit.tpl';
                    break;
                case 'news':
                case 'catalog':
                    $extends = $this->controller.'/index.tpl';
                    break;
                case 'pages':
                case 'home':
                    $extends = $this->controller.'/edit.tpl';
                    break;
                default:
                    $extends = 'index.tpl';
            }
            $this->template->assign('extends',$extends);
            if(isset($this->mod_action)) {
                $this->runAction();
            }
            else {
                $this->adminList();
            }
        }
    }
}