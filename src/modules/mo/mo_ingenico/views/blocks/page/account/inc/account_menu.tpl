[{$smarty.block.parent}]
[{assign var="oxConfig" value=$oView->getConfig() }]
[{if $oxConfig->getShopConfVar('mo_ingenico__use_alias_manager')}]
    <li class="list-group-item[{if $active_link == "mo_ingenico__manage_aliases"}] active[{/if}]">
        <a href="[{ oxgetseourl ident=$oViewConf->getSelfLink()|cat:"cl=mo_ingenico__manage_aliases" }]" rel="nofollow">[{ oxmultilang ident="MO_INGENICO__MANAGE_ALIAS_LINK" }]</a>
    </li>
[{/if}]