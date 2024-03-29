{* if $smarty.get.controller === 'category'}{$dir = 'catalog/'|cat:$smarty.get.controller}{else}{$dir = $smarty.get.controller}{/if*}
{extends file="{$extends}"}
{block name="plugin:content"}
{if {employee_access type="view" class_name=$cClass} eq 1}
    <p class="text-right">
        {#nbr_banner#|ucfirst}: {$banners|count}<a href="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}{if isset($smarty.get.edit)}&amp;action=edit&amp;edit={$smarty.get.edit}{/if}&amp;plugin={$smarty.get.plugin}&amp;mod_action=add" title="{#add_banner#}" class="btn btn-link">
            <span class="fa fa-plus"></span> {#add_banner#|ucfirst}
        </a>
    </p>
    {if $debug}
        {$debug}
    {/if}
    {include file="section/form/table-form-3.tpl" controller=$smarty.get.controller plugin='banner' data=$banners idcolumn='id_banner' ajax_form=true activation=false search=false sortable=true}

    {include file="modal/delete.tpl" plugin='banner' data_type='banner' title={#modal_delete_title#|ucfirst} info_text=true delete_message={#delete_banner_message#}}
    {include file="modal/error.tpl"}
    {else}
        {include file="section/brick/viewperms.tpl"}
    {/if}
{/block}

{block name="foot"}
    {capture name="scriptForm"}/{baseadmin}/min/?f=libjs/vendor/jquery-ui-1.12.min.js,{baseadmin}/template/js/table-form.min.js,plugins/banner/js/admin.min.js{/capture}
    {script src=$smarty.capture.scriptForm type="javascript"}
    <script type="text/javascript">
        window.addEventListener('load', function() {
            typeof banner === "undefined" ? console.log("banner is not defined") : banner.run() ;
        });
    </script>
{/block}