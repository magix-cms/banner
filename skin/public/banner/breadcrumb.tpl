{if !empty($slide)}
    <div id="banner" class="breadcrumb-bg">
        {strip}<picture>
            <!--[if IE 9]><video style="display: none;"><![endif]-->
            <source type="image/webp" sizes="{$slide.img['large']['w']}px" media="(min-width: {$slide.img['medium']['w']}px)" srcset="{$slide.img['large']['src_webp']} {$slide.img['large']['w']}w">
            <source type="image/webp" sizes="{$slide.img['medium']['w']}px" media="(min-width: {$slide.img['small']['w']}px)" srcset="{$slide.img['medium']['src_webp']} {$slide.img['medium']['w']}w">
            <source type="image/webp" sizes="{$slide.img['small']['w']}px" srcset="{$slide.img['small']['src_webp']} {$slide.img['small']['w']}w">
            <source type="image/png" sizes="{$slide.img['large']['w']}px" media="(min-width: {$slide.img['medium']['w']}px)" srcset="{$slide.img['large']['src']} {$slide.img['large']['w']}w">
            <source type="image/png" sizes="{$slide.img['medium']['w']}px" media="(min-width: {$slide.img['small']['w']}px)" srcset="{$slide.img['medium']['src']} {$slide.img['medium']['w']}w">
            <source type="image/png" sizes="{$slide.img['small']['w']}px" srcset="{$slide.img['small']['src']} {$slide.img['small']['w']}w">
            <!--[if IE 9]></video><![endif]-->
            <img src="{$slide.img['small']['src']}" sizes="(min-width: {$slide.img['medium']['w']}px) {$slide.img['large']['w']}px, (min-width: {$slide.img['small']['w']}px) {$slide.img['medium']['w']}px, {$slide.img['small']['w']}px" srcset="{$slide.img['large']['src']} {$slide.img['large']['w']}w,
                            {$slide.img['medium']['src']} {$slide.img['medium']['w']}w,
                            {$slide.img['small']['src']} {$slide.img['small']['w']}w" alt="{$slide.title_slide}" title="{$slide.title_slide}" class="img-responsive lazyload" loading="lazy"/>
            </picture>{/strip}
        {include file="section/brick/breadcrumb.tpl" icon='home' amp=false}
    </div>
{else}
    {include file="section/brick/breadcrumb.tpl" icon='home' amp=false}
{/if}