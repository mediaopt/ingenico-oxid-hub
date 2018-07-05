[{include file="headitem.tpl" title="INGENICO_SETUP_TITLE"|oxmultilangassign}]


[{if $readonly}]
    [{assign var="readonly" value="readonly disabled"}]
    [{else}]
    [{assign var="readonly" value=""}]
    [{/if}]

[{cycle assign="_clear_" values=",2" }]

<div id=liste>
    <form name="myedit" id="myedit" action="[{$oViewConf->getSelfLink()}]" method="post">
        [{$oViewConf->getHiddenSid()}]
        <input type="hidden" name="cl" value="[{$oViewConf->getActiveClassName()}]">
        <input type="hidden" name="fnc" value="save">
        <input type="hidden" name="oxid" value="[{$oxid}]">
        <input type="hidden" name="editval[oxshops__oxid]" value="[{$oxid}]">
        <table cellspacing="0" cellpadding="0" border="0" style="width:100%;height:100%;">

            <tr>
                <td valign="top" class="edittext" style="padding:10px;">
                    <table cellspacing="0" cellpadding="5" border="0" class="edittext" style="text-align: left;">
                        <tr>
                            <td valign="top" class="edittext" width="250" nowrap="">
                                [{oxmultilang ident="INGENICO_PAYMENT_METHODS" }]
                            </td>
                            <td valign="top" class="edittext">
                                [{foreach from=$aPaymentMethods item=pm}]
                            <input type="checkbox" name="ingenico_aPaymentMethods[]" value=[{$pm.id}][{if $pm.checked}]
                                   checked="checked" [{/if }]> [{oxmultilang ident=$pm.name }]<br/>

                                [{* check for payment options *}]
                                [{assign var="mo_ingenico__brands" value=$oView->mo_ingenico__getBrands($pm.id)}]
                                [{if $mo_ingenico__brands|is_array }]
                                [{foreach from=$mo_ingenico__brands item="mo_ingenico__brand"}]
                            <input style="margin-left: 20px;"
                                   type="checkbox"
                                   name="mo_ingenico__paymentOptions[[{$pm.id}]][]"
                                   [{if $oView->mo_ingenico__isBrandActive($pm.id, $mo_ingenico__brand)}]checked="checked"
                                   [{/if}]
                                   value="[{$mo_ingenico__brand}]"/> [{$mo_ingenico__brand}]<br/>
                                [{/foreach}]
                                [{/if}]

                                [{/foreach}]
                            </td>
                        </tr>
                        <!--
        <tr>
          <td valign="top" class="edittext" width="250" nowrap="">[{*oxmultilang ident="INGENICO_ALIAS" *}]</td>
          <td valign="top" class="edittext" nowrap="">
            <fieldset id="ingenicotemplate">
              <legend>
                <input type=hidden name=confbools[ingenico_blAlias] value=false>
                <input type="checkbox" name=confbools[ingenico_blAlias] value="true"[{*if $confbools.ingenico_blAlias == true }] checked=""[{/if*}] onchange="Javascript:templateAlias(this);" [{ $readonly }]>
              </legend>
        [{*oxmultilang ident="INGENICO_ALIAS_USAGE" *}]<br />
        <span id="alias_usage" style="display:inline;">
        [{*foreach from=$languages item=lang}]
          [{ $lang->name }] <input type=text class="editinput" style="width:410px;" name=confstrs[ingenico_sAliasUsage[{ $lang->id }]] value="[{ $lang->aliasUsage }]"><br />
        [{/foreach*}]
        </span>
      </fieldset>
    </td>
  </tr>
        -->
                        <tr>
                            <td valign="top" class="edittext">
                                [{if $start_setup }]
                            <input type="submit" name="save" value="[{oxmultilang ident="START_SETUP" }]" [{
                                   $readonly}]>
                                [{else}]
                            <input type="submit" name="save" value="[{oxmultilang ident="UPDATE_SETUP" }]" [{
                                   $readonly}]>


                                [{/if}]
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </form>
    [{include file="pagenavisnippet.tpl"}]
</div>
[{include file="pagetabsnippet.tpl"}]

[{include file="bottomnaviitem.tpl"}]

[{include file="bottomitem.tpl"}]
