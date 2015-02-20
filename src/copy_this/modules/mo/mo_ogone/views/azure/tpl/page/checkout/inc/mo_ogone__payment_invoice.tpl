<dl>
  <dt>
  <input id="payment_[{$sPaymentID}]" type="radio" name="paymentid" value="[{$sPaymentID}]" [{if $oView->getCheckedPaymentId() == $paymentmethod->oxpayments__oxid->value}]checked[{/if}] />
  <label for="payment_[{$sPaymentID}]"><b>[{ $paymentmethod->oxpayments__oxdesc->value}]</b></label>
  </dt>
  <dd class="[{if $oView->getCheckedPaymentId() == $paymentmethod->oxpayments__oxid->value}]activePayment[{/if}]">
    <ul class="form">
      <li>
        [{assign var="mo_ogone__dynvalue" value=$oView->getDynValue()}]
        <label>[{ oxmultilang ident="BIRTHDATE" }]</label>
        <input type="text" class="textbox" size="20" maxlength="64" name="dynvalue[mo_ogone][invoice][birthdate]" value="[{$mo_ogone__dynvalue.mo_ogone.invoice.birthdate}]">
        <div class="note">(dd.mm.YYYY)</div>
      </li>
      <li>
        <label>[{ oxmultilang ident="MO_OGONE__I_HAVE" }]
          <a style="text-decoration: underline;" target="_blank" href="https://www.billpay.de/kunden/agb">[{ oxmultilang ident="MO_OGONE__BILLPAY_TERMS" }]</a>
          [{ oxmultilang ident="MO_OGONE__READ_AND_ACCEPTED" }]</label>
        <input type="hidden" name="dynvalue[mo_ogone][invoice][agb]" value="0">
        <input type="checkbox" 
               class="textbox" 
               name="dynvalue[mo_ogone][invoice][agb]" 
               value="1" />
      </li>
    </ul>
  </dd>
</dl>