<?php
/**
 * Class plugins_banner_db
 */
class plugins_banner_db {
	/**
	 * @var debug_logger $logger
	 */
	protected debug_logger $logger;

	/**
	 * @param array $config
	 * @param array $params
	 * @return array|bool
	 */
    public function fetchData(array $config, array $params = []) {
		if($config['context'] === 'all') {
			switch ($config['type']) {
				case 'banners':
					$query = 'SELECT 
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
					$query = 'SELECT 
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
					$query = 'SELECT ms.*, msc.*
							FROM mc_banner ms
							JOIN mc_banner_content msc USING(id_banner)
							JOIN mc_lang ml USING(id_lang)
							WHERE ms.id_banner = :id';
					break;
				case 'img':
					$query = 'SELECT ms.id_banner, ms.img_banner FROM mc_banner ms';
					break;
				default:
					return false;
			}

			try {
				return component_routing_db::layer()->fetchAll($query, $params);
			}
			catch (Exception $e) {
				if(!isset($this->logger)) $this->logger = new debug_logger(MP_LOG_DIR);
				$this->logger->log('statement','db',$e->getMessage(),$this->logger::LOG_MONTH);
			}
		}
		elseif($config['context'] === 'one') {
			switch ($config['type']) {
				case 'bannerContent':
					$query = 'SELECT * FROM mc_banner_content WHERE id_banner = :id AND id_lang = :id_lang';
					break;
				case 'lastbanner':
					$query = 'SELECT * FROM mc_banner ORDER BY id_banner DESC LIMIT 0,1';
					break;
				case 'img':
					$query = 'SELECT * FROM mc_banner WHERE id_banner = :id';
					break;
				default:
					return false;
			}

			try {
				return component_routing_db::layer()->fetch($query, $params);
			}
			catch (Exception $e) {
				if(!isset($this->logger)) $this->logger = new debug_logger(MP_LOG_DIR);
				$this->logger->log('statement','db',$e->getMessage(),$this->logger::LOG_MONTH);
			}
		}
		return false;
    }

    /**
     * @param array $config
     * @param array $params
	 * @return bool
     */
    public function insert(array $config, array $params = []): bool {
		switch ($config['type']) {
			case 'banner':
				$query = "INSERT INTO mc_banner(img_banner, module_banner, id_module, order_banner) 
						SELECT 'temp', :module, :id_module, COUNT(id_banner) FROM mc_banner WHERE module_banner = '".$params['module']."'";
				break;
			case 'bannerContent':
				$query = 'INSERT INTO mc_banner_content(id_banner, id_lang, title_banner, desc_banner, url_banner, blank_banner, published_banner)
						VALUES (:id_banner, :id_lang, :title_banner, :desc_banner, :url_banner, :blank_banner, :published_banner)';
				break;
			default:
				return false;
		}

		try {
			component_routing_db::layer()->insert($query,$params);
			return true;
		}
		catch (Exception $e) {
			if(!isset($this->logger)) $this->logger = new debug_logger(MP_LOG_DIR);
			$this->logger->log('statement','db',$e->getMessage(),$this->logger::LOG_MONTH);
		}
		return false;
    }

	/**
	 * @param array $config
	 * @param array $params
	 * @return bool
	 */
    public function update(array $config, array $params = []): bool {
		switch ($config['type']) {
            case 'img':
                $query = 'UPDATE mc_banner
						SET img_banner = :img
						WHERE id_banner = :id';
                break;
			case 'bannerContent':
				$query = 'UPDATE mc_banner_content
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
				$query = 'UPDATE mc_banner 
						SET order_banner = :order_banner
						WHERE id_banner = :id_banner';
				break;
			default:
				return false;
		}

		try {
			component_routing_db::layer()->update($query,$params);
			return true;
		}
		catch (Exception $e) {
			if(!isset($this->logger)) $this->logger = new debug_logger(MP_LOG_DIR);
			$this->logger->log('statement','db',$e->getMessage(),$this->logger::LOG_MONTH);
		}
		return false;
    }

	/**
	 * @param array $config
	 * @param array $params
	 * @return bool
	 */
	protected function delete(array $config, array $params = []): bool {
		switch ($config['type']) {
			case 'banner':
				$query = 'DELETE FROM mc_banner WHERE id_banner IN('.$params['id'].')';
				$params = [];
				break;
			default:
				return false;
		}
		
		try {
			component_routing_db::layer()->delete($query,$params);
			return true;
		}
		catch (Exception $e) {
			if(!isset($this->logger)) $this->logger = new debug_logger(MP_LOG_DIR);
			$this->logger->log('statement','db',$e->getMessage(),$this->logger::LOG_MONTH);
		}
		return false;
	}
}