[{$smarty.block.parent}]
[{if $edit && $edit->oxorder__mo_ogone__status->value}]
  [{oxmultilang ident="MO_OGONE__ORDER_OVERVIEW_STATUS"}]:&nbsp;
  <b>[{$edit->oxorder__mo_ogone__status->value}]</b>
  [{mo_ogone__status_help code=$edit->oxorder__mo_ogone__status->value}]
  <br>
[{/if}]
