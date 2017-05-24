
[{include file="headitem.tpl" title="OGONE_ADMIN_TITLE"|oxmultilangassign}]

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
    <form name="ogonefilter" id="ogonefilter" action="[{$oViewConf->getSelfLink()}]" method="post">
      [{$oViewConf->getHiddenSid()}]
      <input type="hidden" name="cl" value="mo_ogone__interface">
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
                <input name="ogonelogfilter[orderid]" value="[{$ogonelogfilter.orderid}]" type="text" class="edittext" style="width: 50px"></div></div></td>
          <td class="listfilter">
            <div class="r1"><div class="b1">
                <input name="ogonelogfilter[transid]" value="[{$ogonelogfilter.transid}]" type="text" class="edittext" style="width: 90px"></div></div></td>
          <td class="listfilter">
            <div class="r1"><div class="b1">
                <input name="ogonelogfilter[customer_name]" value="[{$ogonelogfilter.customer_name}]" type="text" class="edittext" style="width: 90px"></div></div></td>
          <td class="listfilter">
            <div class="r1"><div class="b1">
                <input name="ogonelogfilter[amount]" value="[{$ogonelogfilter.amount}]" type="text" class="edittext" style="width: 90px"></div></div></td>
          <!--<td class="listfilter">
            <div class="r1"><div class="b1">
            <input name="ogonelogfilter[pm]" value="[{$ogonelogfilter.pm}]" type="text" class="edittext" style="width: 90px"></div></div></td>-->
          <td class="listfilter">
            <div class="r1"><div class="b1">
                <input name="ogonelogfilter[acceptance]" value="[{$ogonelogfilter.acceptance}]" type="text" class="edittext" style="width: 90px"></div></div></td>
          <td class="listfilter">
            <div class="r1"><div class="b1">
                <input name="ogonelogfilter[status]" value="[{$ogonelogfilter.status}]" type="text" class="edittext" style="width: 90px"></div></div></td>
          <td class="listfilter">
            <div class="r1"><div class="b1">
                <input name="ogonelogfilter[cardno]" value="[{$ogonelogfilter.cardno}]" type="text" class="edittext" style="width: 90px"></div></div></td>
          <td class="listfilter">
            <div class="r1"><div class="b1">
                <input name="ogonelogfilter[brand]" value="[{$ogonelogfilter.brand}]" type="text" class="edittext" style="width: 90px"></div></div></td>
          <td class="listfilter">
            <div class="r1"><div class="b1">
                <input name="ogonelogfilter[ed]" value="[{$ogonelogfilter.ed}]" type="text" class="edittext" style="width: 90px"></div></div></td>
          <td class="listfilter">
            <div class="r1"><div class="b1">
                <input name="ogonelogfilter[payid]" value="[{$ogonelogfilter.payid}]" type="text" class="edittext" style="width: 90px"></div></div></td>
          <td class="listfilter">
            <div class="r1"><div class="b1">
                <input name="ogonelogfilter[ncerror]" value="[{$ogonelogfilter.ncerror}]" type="text" class="edittext" style="width: 90px"></div></div></td>
          <td class="listfilter">
            <div class="r1"><div class="b1">
                <input name="ogonelogfilter[trxdate]" value="[{$ogonelogfilter.trxdate}]" type="text" class="edittext" style="width: 90px"></div></div></td>
          </tr>
        <tr>
          <td class="listheader">[{oxmultilang ident="OGONE_DATE" }]</td>
          <td class="listheader">[{oxmultilang ident="OGONE_ORDERID" }]</td>
          <td class="listheader">[{oxmultilang ident="OGONE_TRANSID" }]</td>
          <td class="listheader">[{oxmultilang ident="OGONE_CUSTOMER_NAME" }]</td>
          <td class="listheader">[{oxmultilang ident="OGONE_AMOUNT" }]</td>
          <!--<td class="listheader">[{oxmultilang ident="OGONE_PM" }]</td>-->
          <td class="listheader">[{oxmultilang ident="OGONE_ACCEPTANCE" }]</td>
          <td class="listheader">[{oxmultilang ident="OGONE_STATUS" }]</td>
          <td class="listheader">[{oxmultilang ident="OGONE_CARDNO" }]</td>
          <td class="listheader">[{oxmultilang ident="OGONE_BRAND" }]</td>
          <td class="listheader">[{oxmultilang ident="OGONE_ED" }]</td>
          <td class="listheader">[{oxmultilang ident="OGONE_PAYID" }]</td>
          <td class="listheader">[{oxmultilang ident="OGONE_NCERROR" }]</td>
          <td class="listheader">[{oxmultilang ident="OGONE_TRXDATE" }]</td>
          <td class="listheader">&nbsp;</td>
        </tr>

        [{assign var="blWhite" value=""}]
        [{foreach from=$aLogList item=oItem}]
          [{assign var="listclass" value=listitem$blWhite }]
          <tr>
            <td class="[{ $listclass}]" nowrap="">[{$oItem->mo_ogone__payment_logs__date->value|date_format:"%d.%m.%y&nbsp;%H:%M:%S"}]</td>
            <td class="[{ $listclass}]" nowrap=""><div class="listitemfloating">[{$oItem->mo_ogone__payment_logs__orderid->value}]</div></td>
            <td class="[{ $listclass}]" nowrap=""><div class="listitemfloating">[{$oItem->mo_ogone__payment_logs__transid->value}]</div></td>
            <td class="[{ $listclass}]" nowrap="">[{$oItem->mo_ogone__payment_logs__customer_name->value}]</td>
            <td class="[{ $listclass}]" nowrap="" style="text-align:right">[{$oItem->mo_ogone__payment_logs__amount->value|string_format:"%.2f"}] [{$oItem->mo_ogone__payment_logs__currency->value}]</td>
            <!--<td class="[{ $listclass}]" nowrap="">[{$oItem->mo_ogone__payment_logs__pm->value}]&nbsp;</td>-->
            <td class="[{ $listclass}]" nowrap="">[{$oItem->mo_ogone__payment_logs__acceptance->value}]&nbsp;</td>
            <td class="[{ $listclass}]" nowrap=""><a href="https://secure.ogone.com/ncol/paymentinfos5.asp?branding=Ogone&CSRFSP=%2Fncol%2Ftest%2Fdownload_docs.asp" target="_blank">[{$oItem->mo_ogone__payment_logs__status->value}]</a>&nbsp;</td>
            <td class="[{ $listclass}]" nowrap="">[{$oItem->mo_ogone__payment_logs__cardno->value}]&nbsp;</td>
            <td class="[{ $listclass}]" nowrap="">[{$oItem->mo_ogone__payment_logs__brand->value}]&nbsp;</td>
            <td class="[{ $listclass}]" nowrap="">[{$oItem->mo_ogone__payment_logs__ed->value}]&nbsp;</td>
            <td class="[{ $listclass}]" nowrap="">[{$oItem->mo_ogone__payment_logs__payid->value}]&nbsp;</td>
            <td class="[{ $listclass}]" nowrap=""><a href="https://secure.ogone.com/ncol/paymentinfos5.asp?branding=Ogone&CSRFSP=%2Fncol%2Ftest%2Fdownload_docs.asp" target="_blank">[{$oItem->mo_ogone__payment_logs__ncerror->value}]</a>&nbsp;</td>
            <td class="[{ $listclass}]" nowrap="">[{$oItem->mo_ogone__payment_logs__trxdate->value}]&nbsp;</td>
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