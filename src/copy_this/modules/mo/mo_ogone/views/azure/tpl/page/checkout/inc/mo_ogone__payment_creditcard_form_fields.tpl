
<ul class="form">
  [{* JS-Version *}]
  [{if !$mo_ogone__brand}]
    <li>
      <label>[{ oxmultilang ident="CREDITCARD" }]</label>
      <select name="BRAND" id="mo_ogone__brand">
        [{assign 
        var="mo_ogone__currentPaymentConfig" 
        value=$oView->mo_ogone__getCurrentPaymentConfig('ogone_credit_card')}]
        [{foreach from=$mo_ogone__currentPaymentConfig item="paymentData"}]
          [{if $paymentData.active}]
            <option value="[{$paymentData.brand}]">[{$paymentData.brand}]</option>
          [{/if}]
        [{/foreach}]
      </select>
    </li>
    [{* Non JS-Version *}]
  [{else}]
    <li>
      <label>[{ oxmultilang ident="CREDITCARD" }]: <strong>[{$mo_ogone__brand}]</strong></label>
    </li>
  [{/if}]
  <li>
    <label>[{ oxmultilang ident="NUMBER" }]</label>
    <input type="text" class="js-oxValidate js-oxValidate_notEmpty" size="20" maxlength="35" name="CardNo" value="" autocomplete="off" />
    <p class="oxValidateError">
      <span class="js-oxError_notEmpty">[{ oxmultilang ident="ERROR_MESSAGE_INPUT_NOTALLFIELDS" }]</span>
    </p>
  </li>
  <li>
    <label>[{ oxmultilang ident="BANK_ACCOUNT_HOLDER" }]</label>
    <input type="text" size="20" class="js-oxValidate js-oxValidate_notEmpty" maxlength="50" name="CN" value="[{$oxcmp_user->oxuser__oxfname->value}] [{$oxcmp_user->oxuser__oxlname->value}]">
    <p class="oxValidateError">
      <span class="js-oxError_notEmpty">[{ oxmultilang ident="ERROR_MESSAGE_INPUT_NOTALLFIELDS" }]</span>
    </p>
  </li>
  <li>
    <label>[{ oxmultilang ident="VALID_UNTIL" }]</label>
    <select name="ECOM_CARDINFO_EXPDATE_MONTH">
      <option>01</option>
      <option>02</option>
      <option>03</option>
      <option>04</option>
      <option>05</option>
      <option>06</option>
      <option>07</option>
      <option>08</option>
      <option>09</option>
      <option>10</option>
      <option>11</option>
      <option>12</option>
    </select>

    &nbsp;/&nbsp;

    <select name="ECOM_CARDINFO_EXPDATE_YEAR">
      [{foreach from=$oView->getCreditYears() item=year}]
        <option>[{$year}]</option>
      [{/foreach}]
    </select>
  </li>
  <li>
    <label>[{ oxmultilang ident="CARD_SECURITY_CODE" }]</label>
    <input type="text" class="js-oxValidate js-oxValidate_notEmpty" size="20" maxlength="6" name="CVC" value=""  autocomplete="off" />
    <p class="oxValidateError">
      <span class="js-oxError_notEmpty">[{ oxmultilang ident="ERROR_MESSAGE_INPUT_NOTALLFIELDS" }]</span>
    </p>
    <br>
    <div class="note">[{ oxmultilang ident="MO_OGONE__PAGE_CHECKOUT_PAYMENT_SECURITYCODEDESCRIPTION" }]</div>
  </li>
</ul>