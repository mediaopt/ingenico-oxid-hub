[{$smarty.block.parent}]
[{if $edit && $edit->oxorder__mo_ingenico__status->value}]
  [{oxmultilang ident="MO_INGENICO__ORDER_OVERVIEW_STATUS"}]:&nbsp;
  <b>[{$edit->oxorder__mo_ingenico__status->value}]</b>
  [{include file='inputhelp.tpl' sHelpId=$edit->oxorder__mo_ingenico__status->value sHelpText=$edit->mo_ingenico__getStatusText()}]
  <br>
[{/if}]
