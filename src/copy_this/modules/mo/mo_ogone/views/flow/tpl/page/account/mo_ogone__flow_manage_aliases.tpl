[{capture append="oxidBlock_content"}]
[{assign var="template_title" value="MO_OGONE__MANAGE_ALIAS_HEADER"|oxmultilangassign}]

<h1 class="page-header">[{ oxmultilang ident="MO_OGONE__MANAGE_ALIAS_HEADER" }]</h1>

[{if !$oxcmp_user->mo_ogone__hasValidRegisteredAliasCard()}]

  <p>[{oxmultilang ident="MO_OGONE__NO_CARD"}]</p>

[{else}]

<p><strong>[{oxmultilang ident="MO_OGONE__YOUR_CARD"}]</strong></p>

<table id="mo_ogone__registered_cards" class="table">
  <tr class="sectionHead">
    <th>[{oxmultilang ident="MO_OGONE__BRAND"}]</th>
    <th>[{oxmultilang ident="MO_OGONE__MASKED_NO"}]</th>
    <th>[{oxmultilang ident="MO_OGONE__HOLDER"}]</th>
    <th>[{oxmultilang ident="MO_OGONE__EXP_DATE"}]</th>
    <th>[{oxmultilang ident="MO_OGONE__ACTIONS"}]</th>
  </tr>
    
  [{foreach from=$oxcmp_user->mo_ogone__getCards() item="mo_ogone__alias"}]
  <tr [{if $mo_ogone__alias->mo_ogone__alias__is_default->value}]class="active"[{/if}]>
    <td>
      [{$mo_ogone__alias->mo_ogone__alias__brand->value}]
    </td>
    <td>
      [{$mo_ogone__alias->mo_ogone__alias__cardno->value}]
    </td>
    <td>
      [{$mo_ogone__alias->mo_ogone__alias__cn->value}]
    </td>
    <td>
      [{$mo_ogone__alias->mo_ogone__alias__exp_date->value|date_format:"%m/%Y"}]
    </td>
    <td>
      <a href="[{ $oViewConf->getSelfLink()|cat:"cl=mo_ogone__manage_aliases&fnc=mo_ogone__delete&id="|cat:$mo_ogone__alias->getId() }]">löschen</a>
      [{if !$mo_ogone__alias->mo_ogone__alias__is_default->value}]
      | <a href="[{ $oViewConf->getSelfLink()|cat:"cl=mo_ogone__manage_aliases&fnc=mo_ogone__setDefault&id="|cat:$mo_ogone__alias->getId() }]">Hauptkarte</a>
      [{/if}]
    </td>
  </tr>
  [{/foreach}]
  
</table>

[{/if}]

[{/capture}]

[{capture append="oxidBlock_sidebar"}]
[{include file="page/account/inc/account_menu.tpl" active_link="mo_ogone__manage_aliases"}]
[{/capture}]

[{include file="layout/page.tpl" sidebar="Left"}]
