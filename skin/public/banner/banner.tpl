{banner_data controller='home'}
{$banner = $banners|array_shift}
{if !empty($banner)}
    <div id="banner">
        {strip}<picture>
            <!--[if IE 9]><video style="display: none;"><![endif]-->
            <source type="image/webp" sizes="{$banner.img['large']['w']}px" media="(min-width: {$banner.img['medium']['w']}px)" srcset="{$banner.img['large']['src_webp']} {$banner.img['large']['w']}w">
            <source type="image/webp" sizes="{$banner.img['medium']['w']}px" media="(min-width: {$banner.img['small']['w']}px)" srcset="{$banner.img['medium']['src_webp']} {$banner.img['medium']['w']}w">
            <source type="image/webp" sizes="{$banner.img['small']['w']}px" srcset="{$banner.img['small']['src_webp']} {$banner.img['small']['w']}w">
            <source type="image/png" sizes="{$banner.img['large']['w']}px" media="(min-width: {$banner.img['medium']['w']}px)" srcset="{$banner.img['large']['src']} {$banner.img['large']['w']}w">
            <source type="image/png" sizes="{$banner.img['medium']['w']}px" media="(min-width: {$banner.img['small']['w']}px)" srcset="{$banner.img['medium']['src']} {$banner.img['medium']['w']}w">
            <source type="image/png" sizes="{$banner.img['small']['w']}px" srcset="{$banner.img['small']['src']} {$banner.img['small']['w']}w">
            <!--[if IE 9]></video><![endif]-->
            <img src="{$banner.img['small']['src']}" sizes="(min-width: {$banner.img['medium']['w']}px) {$banner.img['large']['w']}px, (min-width: {$banner.img['small']['w']}px) {$banner.img['medium']['w']}px, {$banner.img['small']['w']}px" srcset="{$banner.img['large']['src']} {$banner.img['large']['w']}w,
                            {$banner.img['medium']['src']} {$banner.img['medium']['w']}w,
                            {$banner.img['small']['src']} {$banner.img['small']['w']}w" alt="{$banner.title_banner}" title="{$banner.title_banner}" class="img-responsive lazyload" loading="lazy"/>
            </picture>{/strip}
    </div>
{/if}