[{include file="headitem.tpl" title="OGONE_ADMIN_TITLE"|oxmultilangassign}]

[{if $readonly}]
  [{assign var="readonly" value="readonly disabled"}]
[{else}]
  [{assign var="readonly" value=""}]
[{/if}]

<form name="transfer" id="transfer" action="[{$oViewConf->getSelfLink()}]" method="post">
  [{$oViewConf->getHiddenSid()}]
  <input type="hidden" name="oxid" value="[{$oxid}]">
  <input type="hidden" name="cl" value="[{$oViewConf->getActiveClassName()}]">
  <input type="hidden" name="fnc" value="">
  <input type="hidden" name="actshop" value="[{ $oViewConf->getActiveShopId() }]">
  <input type="hidden" name="updatenav" value="">
  <input type="hidden" name="editlanguage" value="[{ $editlanguage }]">
</form>

<div style="overflow:auto; display: block">
  <textarea rows="35" cols="200">
    [{$logfile}]
  </textarea>
</div> 
[{if $pagenavi}]
  [{include file="pagenavisnippet.tpl" colspan="13"}]
[{/if}]

[{include file="bottomnaviitem.tpl"}]

[{include file="bottomitem.tpl"}]