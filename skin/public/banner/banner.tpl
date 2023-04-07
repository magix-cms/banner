{if !isset($type)}
    {$type = 'tns'}
{/if}
{if is_array($banners) && $banners != null}
    {if $amp}
        {$ref = $banners|end}
        <amp-carousel id="{$id_bannerr}" class="carousel2"
                      type="banners"
                      autoplay
                      delay="3000"
                      layout="responsive"
                      height="{$ref.img['small']['h']}"
                      width="{$ref.img['small']['w']}"
                      type="banners">
            {foreach $banners as $banner}
                <div class="banner">
                    <amp-img src="{$banner.img['small']['src']}"
                             alt="{$banner.title_banner}"
                             title="{$item.name}"
                             layout="fill" itemprop="image"></amp-img>

                    <div class="caption">
                        <div class="text">
                            <h3 class="h2">{$banner.title_banner}</h3>
                            {if !empty($banner.desc_banner)}
                                <p class="lead">{$banner.desc_banner}</p>
                            {/if}
                        </div>
                    </div>
                </div>
            {/foreach}
        </amp-carousel>
    {else}
        {if $type === 'bootstrap'}
        <div id="{$id_bannerr}" class="carousel banner{if isset($transition)} carousel-{$transition}{/if}" data-ride="carousel"{if isset($interval)} data-interval="{$interval}"{/if}>
            {if count($banners) > 1}
                <div class="carousel-controls">
                <a class="left carousel-control" href="#{$id_bannerr}" role="button" data-banner="prev">
                    <span class="fa fa-angle-left" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <ol class="carousel-indicators">
                    {foreach $banners as $banner}
                        <li data-target="#home-bannerhow" data-banner-to="{$banner@index}"{if $banner@first} class="active"{/if}></li>
                    {/foreach}
                </ol>
                <a class="right carousel-control" href="#{$id_bannerr}" role="button" data-banner="next">
                    <span class="fa fa-angle-right" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
                </div>{/if}
            <div class="carousel-inner" role="listbox">
                {foreach $banners as $banner}
                    <div class="item{if $banner@first} active{/if}">
                        {strip}
                        <picture>
                            <!--[if IE 9]><video style="display: none;"><![endif]-->
                            <source sizes="100vw"
                                    media="(min-width: {$banner.img['medium']['w']}px)"
                                    srcset="{$banner.img['large']['src']} {$banner.img['large']['w']}w">
                            <source sizes="100vw"
                                    media="(min-width: {$banner.img['small']['w']}px)"
                                    srcset="{$banner.img['medium']['src']} {$banner.img['medium']['w']}w">
                            <source sizes="100vw"
                                    srcset="{$banner.img['small']['src']} {$banner.img['small']['w']}w">
                            <!--[if IE 9]></video><![endif]-->
                            <img src="{$banner.imgSrc['small']}"
                                 sizes="100vw"
                                 srcset="{$banner.img['large']['src']} {$banner.img['large']['w']}w,
                                    {$banner.img['medium']['src']} {$banner.img['medium']['w']}w,
                                    {$banner.img['small']['src']} {$banner.img['small']['w']}w"
                                 alt="{$banner.title_banner}" title="{$banner.title_banner}" />
                        </picture>{/strip}
                        <div class="carousel-caption">
                            <div>
                                <h3 class="h2">{$banner.title_banner}</h3>
                                {if !empty($banner.desc_banner)}
                                    <p class="lead">{$banner.desc_banner}</p>
                                {/if}
                            </div>
                        </div>
                        {if isset($banner.url_banner) && !empty($banner.url_banner)}
                            <a href="{$banner.url_banner}" title="{$key.title_banner}" class="all-hover{if $banner.blank_banner} targetblank{/if}">{$banner.title_banner}</a>
                        {/if}
                    </div>
                {/foreach}
            </div>
        </div>
        {elseif $type === 'owl-carousel'}
        <div id="{$id_bannerr}" class="owl-banner">
            <div class="owl-carousel owl-theme">
            {foreach $banners as $k => $banner}
                <div class="banner" data-dot="<span><span>banner {$k}</span></span>">
                    {strip}<picture>
                    <!--[if IE 9]><video style="display: none;"><![endif]-->
                    <source type="image/webp" sizes="{$banner.img['large']['w']}px" media="(min-width: 1200px)" srcset="{$banner.img['large']['src_webp']} {$banner.img['large']['w']}w">
                    <source type="image/webp" sizes="{$banner.img['medium']['w']}px" media="(min-width: 768px)" srcset="{$banner.img['medium']['src_webp']} {$banner.img['medium']['w']}w">
                    <source type="image/webp" sizes="{$banner.img['small']['w']}px" srcset="{$banner.img['small']['src_webp']} {$banner.img['small']['w']}w">
                    <source type="image/png" sizes="{$banner.img['large']['w']}px" media="(min-width: 1200px)" srcset="{$banner.img['large']['src']} {$banner.img['large']['w']}w">
                    <source type="image/png" sizes="{$banner.img['medium']['w']}px" media="(min-width: 768px)" srcset="{$banner.img['medium']['src']} {$banner.img['medium']['w']}w">
                    <source type="image/png" sizes="{$banner.img['small']['w']}px" srcset="{$banner.img['small']['src']} {$banner.img['small']['w']}w">
                    <!--[if IE 9]></video><![endif]-->
                    <img src="{$banner.img['small']['src']}" sizes="(min-width: 1200px) {$banner.img['large']['w']}px, (min-width: 768px) {$banner.img['medium']['w']}px, {$banner.img['small']['w']}px" srcset="{$banner.img['large']['src']} {$banner.img['large']['w']}w,
                                {$banner.img['medium']['src']} {$banner.img['medium']['w']}w,
                                {$banner.img['small']['src']} {$banner.img['small']['w']}w" alt="{$banner.title_banner}" title="{$banner.title_banner}" class="img-responsive lazyload" />
                    </picture>{/strip}
                    <div class="carousel-caption">
                        <h3>{$banner.title_banner}</h3>
                        {if !empty($banner.desc_banner)}
                            <p>{$banner.desc_banner}</p>
                        {/if}
                    </div>
                    {if isset($banner.url_banner) && !empty($banner.url_banner)}
                        <a href="{$banner.url_banner}" title="{$key.title_banner}" class="all-hover{if $banner.blank_banner} targetblank{/if}">{$banner.title_banner}</a>
                    {/if}
                </div>
            {/foreach}
            </div>
            <div class="owl-banner-nav">
                <div class="owl-banner-dots"></div>
            </div>
        </div>
        {elseif $type === 'tns'}
            <div id="{$id_bannerr}">
            <div class="slideshow">
                {foreach $banners as $k => $banner}
                    <div class="banner" data-dot="<span><span>banner {$k}</span></span>">
                        {strip}<picture>
                            <!--[if IE 9]><video style="display: none;"><![endif]-->
                            <source type="image/webp" sizes="{$banner.img['large']['w']}px" media="(min-width: 1200px)" srcset="{$banner.img['large']['src_webp']} {$banner.img['large']['w']}w">
                            <source type="image/webp" sizes="{$banner.img['medium']['w']}px" media="(min-width: 768px)" srcset="{$banner.img['medium']['src_webp']} {$banner.img['medium']['w']}w">
                            <source type="image/webp" sizes="{$banner.img['small']['w']}px" srcset="{$banner.img['small']['src_webp']} {$banner.img['small']['w']}w">
                            <source type="image/png" sizes="{$banner.img['large']['w']}px" media="(min-width: 1200px)" srcset="{$banner.img['large']['src']} {$banner.img['large']['w']}w">
                            <source type="image/png" sizes="{$banner.img['medium']['w']}px" media="(min-width: 768px)" srcset="{$banner.img['medium']['src']} {$banner.img['medium']['w']}w">
                            <source type="image/png" sizes="{$banner.img['small']['w']}px" srcset="{$banner.img['small']['src']} {$banner.img['small']['w']}w">
                            <!--[if IE 9]></video><![endif]-->
                            <img src="{$banner.img['small']['src']}" sizes="(min-width: 1200px) {$banner.img['large']['w']}px, (min-width: 768px) {$banner.img['medium']['w']}px, {$banner.img['small']['w']}px" srcset="{$banner.img['large']['src']} {$banner.img['large']['w']}w,
                                {$banner.img['medium']['src']} {$banner.img['medium']['w']}w,
                                {$banner.img['small']['src']} {$banner.img['small']['w']}w" alt="{$banner.title_banner}" title="{$banner.title_banner}" class="img-responsive lazyload" />
                            </picture>{/strip}
                        <div class="carousel-caption">
                            <h3>{$banner.title_banner}</h3>
                            {if !empty($banner.desc_banner)}
                                <p>{$banner.desc_banner}</p>
                            {/if}
                        </div>
                        {if isset($banner.url_banner) && !empty($banner.url_banner)}
                            <a href="{$banner.url_banner}" title="{$key.title_banner}" class="all-hover{if $banner.blank_banner} targetblank{/if}">{$banner.title_banner}</a>
                        {/if}
                    </div>
                {/foreach}
            </div>
        </div>
        {/if}
    {/if}
{/if}