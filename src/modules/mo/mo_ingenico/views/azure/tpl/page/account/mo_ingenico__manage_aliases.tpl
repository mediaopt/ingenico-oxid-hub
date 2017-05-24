[{capture append="oxidBlock_content"}]

[{oxstyle include=$oViewConf->getModuleUrl('mo_ingenico','out/src/css/mo_ingenico.css')}]

[{assign var="template_title" value="MO_INGENICO__MANAGE_ALIAS_HEADER"|oxmultilangassign}]

<h1 class="pageHead">[{ oxmultilang ident="MO_INGENICO__MANAGE_ALIAS_HEADER" }]</h1>

[{if $mo_ingenico__successMessage}]
  <div class="status success corners">
    <p>[{oxmultilang ident=$mo_ingenico__successMessage}]</p>
  </div>
[{/if}]

[{if $mo_ingenico__errorMessage}]
  <div class="status error corners">
    <p>[{oxmultilang ident=$mo_ingenico__errorMessage}]</p>
  </div>
[{/if}]

[{if !$oxcmp_user->mo_ingenico__hasValidRegisteredAliasCard()}]

  <p>[{oxmultilang ident="MO_INGENICO__NO_CARD"}]</p>

[{else}]

<p><strong>[{oxmultilang ident="MO_INGENICO__YOUR_CARD"}]</strong></p>

<table id="mo_ingenico__registered_cards">
  <tr class="sectionHead">
    <th>[{oxmultilang ident="MO_INGENICO__MASKED_NO"}]</th>
    <th>[{oxmultilang ident="MO_INGENICO__HOLDER"}]</th>
    <th>[{oxmultilang ident="MO_INGENICO__EXP_DATE"}]</th>
    <th>[{oxmultilang ident="MO_INGENICO__ACTIONS"}]</th>
  </tr>
    
  [{foreach from=$oxcmp_user->mo_ingenico__getCards() item="mo_ingenico__alias"}]
  <tr>
    <td [{if $mo_ingenico__alias->mo_ingenico__alias__is_default->value}]style="font-weight: bold;"[{/if}]>
      [{$mo_ingenico__alias->mo_ingenico__alias__cardno->value}]
  </td>
  <td [{if $mo_ingenico__alias->mo_ingenico__alias__is_default->value}]style="font-weight: bold;"[{/if}]>
    [{$mo_ingenico__alias->mo_ingenico__alias__cn->value}]
  </td>
  <td [{if $mo_ingenico__alias->mo_ingenico__alias__is_default->value}]style="font-weight: bold;"[{/if}]>
    [{$mo_ingenico__alias->mo_ingenico__alias__exp_date->value|date_format:"%m/%Y"}]
  </td>
  <td>
    <a href="[{ $oViewConf->getSelfLink()|cat:"cl=mo_ingenico__manage_aliases&fnc=mo_ingenico__delete&id="|cat:$mo_ingenico__alias->getId() }]">löschen</a>
    [{if !$mo_ingenico__alias->mo_ingenico__alias__is_default->value}]
    | <a href="[{ $oViewConf->getSelfLink()|cat:"cl=mo_ingenico__manage_aliases&fnc=mo_ingenico__setDefault&id="|cat:$mo_ingenico__alias->getId() }]">Hauptkarte</a>
    [{/if}]
  </td>
</tr>
[{/foreach}]
  
</table>

[{/if}]

[{/capture}]

[{capture append="oxidBlock_sidebar"}]
[{include file="page/account/inc/account_menu.tpl" active_link="mo_ingenico__manage_aliases"}]
[{/capture}]

[{include file="layout/page.tpl" sidebar="Left"}]
