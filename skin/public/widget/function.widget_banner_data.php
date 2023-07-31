<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */
/**
 * Smarty {widget_banner_data} function plugin
 *
 * Type:     function
 * Name:     widget_banner_data
 * Date:     07/09/2011
 * Update    16/05/2017
 * Purpose:  banner
 * Dependance: banner
 * Examples with nivobannerr:
 * <div id="bannerr-home-wrapper" class="bannerr-wrapper theme-light container">
    <div id="bannerr-home" class="nivobannerr">
        {widget_banner_data id={getlang} type="root"}
        {if $collection_banner != null}
            {foreach $collection_banner as $key}
                {if $key.uri_banner != null}
                    <a href="{$key.uri_banner}" title="{$key.title_banner}">
                    <img src="/upload/banner/{$key.img_banner}" alt="{$key.title_banner}" />
                    </a>
                {else}
                    <img src="/upload/banner/{$key.img_banner}" alt="{$key.title_banner}" />
                {/if}
            {/foreach}
        {/if}
    </div>
  </div>
 * Example with revolutionbannerr
 * {widget_banner_data}
    {if $collectionbanner != null}
        <div class="tp-banner-container hidden-xs">
        {if !isset($delay)}
        {assign var='delay' value="3000"}
        {/if}
            <div class="tp-banner">
            <ul>
            {foreach $collectionbanner as $key}
                <li data-transition="fade" data-slotamount="7" data-masterspeed="500" data-delay="{$delay}"{if isset($key.uri_banner) && !empty($key.uri_banner)} data-link="{$key.uri_banner}" data-target="_blank"{/if} title="{$key.title_banner}">
                    <img src="/skin/{template}/img/bannerr/dummy.png"  data-lazyload="/upload/banner/{$key.img_banner}" alt="{$key.title_banner}" data-bgfit="cover" data-bgposition="center center" data-bgrepeat="no-repeat" title="{$key.title_banner}" />
                    <div class="tp-caption fade bannerr"
                    data-x="center"
                    data-y="bottom"
                    data-speed="800"
                    data-start="800"
                    data-easing="Power4.easeOut"
                    data-endspeed="500"
                    data-endeasing="Power4.easeIn"
                    data-captionhidden="on"
                    style="z-index: 4">
                        <div class="bannerr-desc">
                            <h5>{$key.title_banner}</h5>
                            {if $key.desc_banner}
                            <p>{$key.desc_banner}</p>
                            {/if}
                        </div>
                    </div>
                </li>
            {/foreach}
            </ul>
            </div>
        </div>
    {/if}
 * Output:   
 * @link 
 * @author   Gerits Aurelien
 * @version  1.0
 * @param array
 * @param Smarty
 * @return string
 */
function smarty_function_widget_banner_data($params, $smarty){
	$modelTemplate = $smarty->tpl_vars['modelTemplate']->value instanceof frontend_model_template ? $smarty->tpl_vars['modelTemplate']->value : new frontend_model_template();
	$banner = new plugins_banner_public($modelTemplate);
	$assign = isset($params['assign']) ? $params['assign'] : 'banners';
	$smarty->assign($assign,$banner->getbanners($params));
}