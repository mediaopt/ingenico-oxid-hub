
[{include file="headitem.tpl" title="INGENICO_ADMIN_TITLE"|oxmultilangassign}]

<script type="text/javascript">
  <!--
  [{if $updatelist == 1}]
  UpdateList('[{$oxid}]');
  [{/if}]

  function UpdateList(sID)
  {
    var oSearch = parent.list.document.getElementById("search");
    oSearch.oxid.value=sID;
    oSearch.submit();
  }

  function Edit(sID, sCl)
  {
    var oTransfer = document.getElementById("transfer");
    oTransfer.oxid.value = sID;
    oTransfer.cl.value = 'admin_'+sCl;
    oTransfer.target = '_parent';
    oTransfer.submit();
  }
  //-->
</script>

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

<form name="myedit" id="myedit" action="[{$oViewConf->getSelfLink() }]" method="post">
  [{ $oViewConf->getHiddenSid() }]
  <input type="hidden" name="cl" value="[{$oViewConf->getActiveClassName()}]">
  <input type="hidden" name="fnc" value="">
  <input type="hidden" name="oxid" value="[{ $oxid }]">
  <input type="hidden" name="editval[oxshops__oxid]" value="[{ $oxid }]">

  <div style="overflow:auto; display: block">
    <form name="ingenicofilter" id="ingenicofilter" action="[{$oViewConf->getSelfLink()}]" method="post">
      [{$oViewConf->getHiddenSid()}]
      <input type="hidden" name="cl" value="mo_ingenico__interface">
      <input type="hidden" name="fnc" value="setFilter">
      <input type="hidden" name="oxid" value="[{$oxid}]">
      <input type="hidden" name="editval[oxshops__oxid]" value="[{$oxid}]">
      <input type="hidden" name="lstrt" value="[{$lstrt}]">

      <table>
        <tr class="listitem">
          <td class="listfilter">
            <div class="r1"><div class="b1">
                <input type="submit" class="edittext" value="Filter" style="width: 140px"></div></div></td>
          <td class="listfilter">
            <div class="r1"><div class="b1">
                <input name="ingenicologfilter[orderid]" value="[{$ingenicologfilter.orderid}]" type="text" class="edittext" style="width: 50px"></div></div></td>
          <td class="listfilter">
            <div class="r1"><div class="b1">
                <input name="ingenicologfilter[transid]" value="[{$ingenicologfilter.transid}]" type="text" class="edittext" style="width: 90px"></div></div></td>
          <td class="listfilter">
            <div class="r1"><div class="b1">
                <input name="ingenicologfilter[customer_name]" value="[{$ingenicologfilter.customer_name}]" type="text" class="edittext" style="width: 90px"></div></div></td>
          <td class="listfilter">
            <div class="r1"><div class="b1">
                <input name="ingenicologfilter[amount]" value="[{$ingenicologfilter.amount}]" type="text" class="edittext" style="width: 90px"></div></div></td>
          <!--<td class="listfilter">
            <div class="r1"><div class="b1">
            <input name="ingenicologfilter[pm]" value="[{$ingenicologfilter.pm}]" type="text" class="edittext" style="width: 90px"></div></div></td>-->
          <td class="listfilter">
            <div class="r1"><div class="b1">
                <input name="ingenicologfilter[acceptance]" value="[{$ingenicologfilter.acceptance}]" type="text" class="edittext" style="width: 90px"></div></div></td>
          <td class="listfilter">
            <div class="r1"><div class="b1">
                <input name="ingenicologfilter[status]" value="[{$ingenicologfilter.status}]" type="text" class="edittext" style="width: 90px"></div></div></td>
          <td class="listfilter">
            <div class="r1"><div class="b1">
                <input name="ingenicologfilter[cardno]" value="[{$ingenicologfilter.cardno}]" type="text" class="edittext" style="width: 90px"></div></div></td>
          <td class="listfilter">
            <div class="r1"><div class="b1">
                <input name="ingenicologfilter[brand]" value="[{$ingenicologfilter.brand}]" type="text" class="edittext" style="width: 90px"></div></div></td>
          <td class="listfilter">
            <div class="r1"><div class="b1">
                <input name="ingenicologfilter[ed]" value="[{$ingenicologfilter.ed}]" type="text" class="edittext" style="width: 90px"></div></div></td>
          <td class="listfilter">
            <div class="r1"><div class="b1">
                <input name="ingenicologfilter[payid]" value="[{$ingenicologfilter.payid}]" type="text" class="edittext" style="width: 90px"></div></div></td>
          <td class="listfilter">
            <div class="r1"><div class="b1">
                <input name="ingenicologfilter[ncerror]" value="[{$ingenicologfilter.ncerror}]" type="text" class="edittext" style="width: 90px"></div></div></td>
          <td class="listfilter">
            <div class="r1"><div class="b1">
                <input name="ingenicologfilter[trxdate]" value="[{$ingenicologfilter.trxdate}]" type="text" class="edittext" style="width: 90px"></div></div></td>
          </tr>
        <tr>
          <td class="listheader">[{oxmultilang ident="INGENICO_DATE" }]</td>
          <td class="listheader">[{oxmultilang ident="INGENICO_ORDERID" }]</td>
          <td class="listheader">[{oxmultilang ident="INGENICO_TRANSID" }]</td>
          <td class="listheader">[{oxmultilang ident="INGENICO_CUSTOMER_NAME" }]</td>
          <td class="listheader">[{oxmultilang ident="INGENICO_AMOUNT" }]</td>
          <!--<td class="listheader">[{oxmultilang ident="INGENICO_PM" }]</td>-->
          <td class="listheader">[{oxmultilang ident="INGENICO_ACCEPTANCE" }]</td>
          <td class="listheader">[{oxmultilang ident="INGENICO_STATUS" }]</td>
          <td class="listheader">[{oxmultilang ident="INGENICO_CARDNO" }]</td>
          <td class="listheader">[{oxmultilang ident="INGENICO_BRAND" }]</td>
          <td class="listheader">[{oxmultilang ident="INGENICO_ED" }]</td>
          <td class="listheader">[{oxmultilang ident="INGENICO_PAYID" }]</td>
          <td class="listheader">[{oxmultilang ident="INGENICO_NCERROR" }]</td>
          <td class="listheader">[{oxmultilang ident="INGENICO_TRXDATE" }]</td>
          <td class="listheader">&nbsp;</td>
        </tr>

        [{assign var="blWhite" value=""}]
        [{foreach from=$aLogList item=oItem}]
          [{assign var="listclass" value=listitem$blWhite }]
          <tr>
            <td class="[{ $listclass}]" nowrap="">[{$oItem->mo_ingenico__payment_logs__date->value|date_format:"%d.%m.%y&nbsp;%H:%M:%S"}]</td>
            <td class="[{ $listclass}]" nowrap=""><div class="listitemfloating">[{$oItem->mo_ingenico__payment_logs__orderid->value}]</div></td>
            <td class="[{ $listclass}]" nowrap=""><div class="listitemfloating">[{$oItem->mo_ingenico__payment_logs__transid->value}]</div></td>
            <td class="[{ $listclass}]" nowrap="">[{$oItem->mo_ingenico__payment_logs__customer_name->value}]</td>
            <td class="[{ $listclass}]" nowrap="" style="text-align:right">[{$oItem->mo_ingenico__payment_logs__amount->value|string_format:"%.2f"}] [{$oItem->mo_ingenico__payment_logs__currency->value}]</td>
            <!--<td class="[{ $listclass}]" nowrap="">[{$oItem->mo_ingenico__payment_logs__pm->value}]&nbsp;</td>-->
            <td class="[{ $listclass}]" nowrap="">[{$oItem->mo_ingenico__payment_logs__acceptance->value}]&nbsp;</td>
            <td class="[{ $listclass}]" nowrap=""><a href="https://secure.ogone.com/ncol/paymentinfos5.asp?branding=Ingenico&CSRFSP=%2Fncol%2Ftest%2Fdownload_docs.asp" target="_blank">[{$oItem->mo_ingenico__payment_logs__status->value}]</a>&nbsp;</td>
            <td class="[{ $listclass}]" nowrap="">[{$oItem->mo_ingenico__payment_logs__cardno->value}]&nbsp;</td>
            <td class="[{ $listclass}]" nowrap="">[{$oItem->mo_ingenico__payment_logs__brand->value}]&nbsp;</td>
            <td class="[{ $listclass}]" nowrap="">[{$oItem->mo_ingenico__payment_logs__ed->value}]&nbsp;</td>
            <td class="[{ $listclass}]" nowrap="">[{$oItem->mo_ingenico__payment_logs__payid->value}]&nbsp;</td>
            <td class="[{ $listclass}]" nowrap=""><a href="https://secure.ogone.com/ncol/paymentinfos5.asp?branding=Ingenico&CSRFSP=%2Fncol%2Ftest%2Fdownload_docs.asp" target="_blank">[{$oItem->mo_ingenico__payment_logs__ncerror->value}]</a>&nbsp;</td>
            <td class="[{ $listclass}]" nowrap="">[{$oItem->mo_ingenico__payment_logs__trxdate->value}]&nbsp;</td>
            <td class="[{ $listclass}]">&nbsp;</td>
          </tr>

        [{assign var="blWhite" value="2"}]
          [{if $blWhite == "2"}]
            [{assign var="blWhite" value=""}]
          [{/if}]
        [{/foreach}]
      </table>
    </form>
  </div> 
  [{if $pagenavi}]
    [{include file="pagenavisnippet.tpl" colspan="13"}]
  [{/if}]

  [{include file="bottomnaviitem.tpl"}]

  [{include file="bottomitem.tpl"}]