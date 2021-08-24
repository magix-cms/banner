<?php
/**
 * Class plugins_banner_db
 */
class plugins_banner_db
{
	/**
	 * @param array $config
	 * @param bool $params
	 * @return mixed|null
	 */
    public function fetchData(array $config, $params = false)
	{
        $sql = '';

        if(is_array($config)) {
            if($config['context'] === 'all') {
            	switch ($config['type']) {
					case 'banners':
						$sql = 'SELECT 
									id_banner,
									url_banner,
									img_banner,
									title_banner,
									desc_banner
 								FROM mc_banner ms
								LEFT JOIN mc_banner_content msc USING(id_banner)
								LEFT JOIN mc_lang ml USING(id_lang)
								WHERE ml.id_lang = :lang
								  AND ms.module_banner = :module
                                  AND ms.id_module '.(empty($params['id_module']) ? 'IS NULL' : '= :id_module').'
								ORDER BY ms.order_banner';
						if(empty($params['id_module'])) unset($params['id_module']);
						break;
					case 'activebanners':
						$sql = 'SELECT 
									id_banner,
									url_banner,
									blank_banner,
									img_banner,
									title_banner,
									desc_banner
 								FROM mc_banner ms
								LEFT JOIN mc_banner_content msc USING(id_banner)
								LEFT JOIN mc_lang ml USING(id_lang)
								WHERE iso_lang = :lang
								  AND ms.module_banner = :module_banner
								  AND ms.id_module '.(empty($params['id_module']) ? 'IS NULL' : '= :id_module').'
								  AND published_banner = 1
								ORDER BY order_banner';
                        if(empty($params['id_module'])) unset($params['id_module']);
						break;
					case 'bannerContent':
						$sql = 'SELECT ms.*, msc.*
                    			FROM mc_banner ms
                    			JOIN mc_banner_content msc USING(id_banner)
                    			JOIN mc_lang ml USING(id_lang)
                    			WHERE ms.id_banner = :id';
						break;
					case 'img':
						$sql = 'SELECT ms.id_banner, ms.img_banner FROM mc_banner ms WHERE ms.img_banner IS NOT NULL';
						break;
				}

                return $sql ? component_routing_db::layer()->fetchAll($sql,$params) : null;
            }
            elseif($config['context'] === 'one') {
				switch ($config['type']) {
					case 'bannerContent':
						$sql = 'SELECT * FROM mc_banner_content WHERE id_banner = :id AND id_lang = :id_lang';
						break;
					case 'lastbanner':
						$sql = 'SELECT * FROM mc_banner ORDER BY id_banner DESC LIMIT 0,1';
						break;
					case 'img':
						$sql = 'SELECT * FROM mc_banner WHERE id_banner = :id';
						break;
				}

                return $sql ? component_routing_db::layer()->fetch($sql,$params) : null;
            }
        }
    }

    /**
     * @param array $config
     * @param array $params
	 * @return bool|string
     */
    public function insert(array $config, $params = [])
    {
		$sql = '';

		switch ($config['type']) {
			case 'banner':
				$sql = "INSERT INTO mc_banner(img_banner, module_banner, id_module, order_banner) 
						SELECT 'temp', :module, :id_module, COUNT(id_banner) FROM mc_banner WHERE module_banner = '".$params['module']."'";
				break;
			case 'bannerContent':
				$sql = 'INSERT INTO mc_banner_content(id_banner, id_lang, title_banner, desc_banner, url_banner, blank_banner, published_banner)
						VALUES (:id_banner, :id_lang, :title_banner, :desc_banner, :url_banner, :blank_banner, :published_banner)';
				break;
		}

		if($sql === '') return 'Unknown request asked';

		try {
			component_routing_db::layer()->insert($sql,$params);
			return true;
		}
		catch (Exception $e) {
			return 'Exception : '.$e->getMessage();
		}
    }

	/**
	 * @param array $config
	 * @param array $params
	 * @return bool|string
	 */
    public function update(array $config, $params = [])
    {
		$sql = '';

		switch ($config['type']) {
            case 'img':
                $sql = 'UPDATE mc_banner
						SET img_banner = :img
						WHERE id_banner = :id';
                break;
			case 'bannerContent':
				$sql = 'UPDATE mc_banner_content
						SET 
							title_banner = :title_banner,
							desc_banner = :desc_banner,
							url_banner = :url_banner,
							blank_banner = :blank_banner,
							published_banner = :published_banner
						WHERE id_banner_content = :id 
						AND id_lang = :id_lang';
				break;
			case 'order':
				$sql = 'UPDATE mc_banner 
						SET order_banner = :order_banner
						WHERE id_banner = :id_banner';
				break;
		}

		if($sql === '') return 'Unknown request asked';

		try {
			component_routing_db::layer()->update($sql,$params);
			return true;
		}
		catch (Exception $e) {
			return 'Exception : '.$e->getMessage();
		}
    }

	/**
	 * @param array $config
	 * @param array $params
	 * @return bool|string
	 */
	protected function delete(array $config, $params = [])
    {
		$sql = '';

		switch ($config['type']) {
			case 'banner':
				$sql = 'DELETE FROM mc_banner WHERE id_banner IN('.$params['id'].')';
				$params = [];
				break;
		}

		if($sql === '') return 'Unknown request asked';

		try {
			component_routing_db::layer()->delete($sql,$params);
			return true;
		}
		catch (Exception $e) {
			return 'Exception : '.$e->getMessage();
		}
	}
}