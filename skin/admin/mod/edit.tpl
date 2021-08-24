{* if $smarty.get.controller === 'category'}{$dir = 'catalog/'|cat:$smarty.get.controller}{else}{$dir = $smarty.get.controller}{/if}
{extends file="{$dir}/edit.tpl"*}
{extends file="{$extends}"}
{block name="plugin:content"}
    {if {employee_access type="edit" class_name=$cClass} eq 1}
        {if $debug}
            {$debug}
        {/if}
        {include file="mod/banner.tpl" controller="banner"}
    {/if}
{/block}

{block name="foot"}
    {capture name="vendorsFiles"}{strip}
        {baseadmin}/template/js/img-drop.min.js
    {/strip}{/capture}
    {capture name="vendors"}{strip}
        /{baseadmin}/min/?f={$smarty.capture.vendorsFiles}
    {/strip}{/capture}
    {script src=$smarty.capture.vendors type="vendors"}
    {capture name="mod_url"}{strip}
        {$smarty.server.SCRIPT_NAME}
        ?controller={$smarty.get.controller}
        {if isset($smarty.get.action)}&action={$smarty.get.action}{/if}
        {if isset($smarty.get.edit)}&edit={$smarty.get.edit}{/if}
        {if isset($smarty.get.tabs)}&tabs={$smarty.get.tabs}{/if}
        {if isset($smarty.get.tab)}&tab={$smarty.get.tab}{/if}
        &plugin={$smarty.get.plugin}
    {/strip}{/capture}
    <script type="text/javascript">
        window.addEventListener('load', function() {
            var controller = "{$smarty.capture.mod_url}";
            typeof globalForm === "undefined" ? console.log("globalForm is not defined") : globalForm.run(controller);
        });
    </script>
{/block}