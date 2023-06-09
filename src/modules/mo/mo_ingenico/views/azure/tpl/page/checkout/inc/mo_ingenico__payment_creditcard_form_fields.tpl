
<ul class="form">
  [{* JS-Version *}]
  [{assign var="oxConfig" value=$oView->getConfig()}]
  [{if !$mo_ingenico__brand || !$oxConfig->getShopConfVar('mo_ingenico__use_hidden_auth')}]
    <li>
      <label>[{oxmultilang ident="CREDITCARD" }]</label>

      [{if $oxConfig->getShopConfVar('mo_ingenico__use_hidden_auth')}]
        [{if $oxConfig->getShopConfVar('mo_ingenico__use_iframe')}]
            <select name="CARD.BRAND" id="mo_ingenico__brand">
        [{else}]
            <select name="BRAND" id="mo_ingenico__brand">
        [{/if}]
      [{else}]
        <select name="dynvalue[mo_ingenico][cc][brand]" id="mo_ingenico__brand">
      [{/if}]
        [{if $oxConfig->getShopConfVar('mo_ingenico__use_alias_manager')}]
          [{assign var="aliases" value=$oxcmp_user->mo_ingenico__getCards()}]
          [{foreach from=$oxcmp_user->mo_ingenico__getCards() item="alias"}]
            <option value="alias_[{$alias->mo_ingenico__alias__alias->value}]">[{$alias->mo_ingenico__alias__brand->value}]: [{$alias->mo_ingenico__alias__cardno->value}]  [{oxmultilang ident="VALID_UNTIL"}] [{$alias->getExpirationData()}]</option>
          [{/foreach}]
        [{/if}]
        [{assign
        var="mo_ingenico__currentPaymentConfig" 
        value=$oView->mo_ingenico__getCurrentPaymentConfig('ingenico_credit_card')}]
        [{foreach from=$mo_ingenico__currentPaymentConfig.brand item="brand"}]
          [{if $oxConfig->getShopConfVar('mo_ingenico__use_hidden_auth') && $oxConfig->getShopConfVar('mo_ingenico__use_iframe') && $brand == 'MasterCard'}]
              <option value="Eurocard">[{$brand}]</option>
          [{ else }]
              <option value="[{$brand}]">[{$brand}]</option>
          [{/if}]
        [{/foreach}]
      </select>
    </li>
    [{* Non JS-Version *}]
  [{else}]
    <li>
      <label>[{oxmultilang ident="CREDITCARD" }]: <strong>[{$mo_ingenico__brand}]</strong></label>
    </li>
  [{/if}]
  [{if $oxConfig->getShopConfVar('mo_ingenico__use_hidden_auth') && $oxConfig->getShopConfVar('mo_ingenico__use_iframe')}]
    <li>
        <div>
            <iframe id="mo_ingenico__iframe" src="" width="350px" height="330px">    
            </iframe>
        </div>
    </li>

  [{/if}]
  [{* Hidden Auth using form on shopsite (not via iFrame) *}]
  [{if $oxConfig->getShopConfVar('mo_ingenico__use_hidden_auth') && !$oxConfig->getShopConfVar('mo_ingenico__use_iframe')}]
  <div id="mo_ingenico__creditcard_fields">
    <li>
      <label>[{oxmultilang ident="NUMBER" }]</label>
      <input type="text" class="js-oxValidate js-oxValidate_notEmpty" size="20" maxlength="35" name="CardNo" value="" autocomplete="off" />
      <p class="oxValidateError">
        <span class="js-oxError_notEmpty">[{oxmultilang ident="ERROR_MESSAGE_INPUT_NOTALLFIELDS" }]</span>
      </p>
    </li>
    <li>
      <label>[{oxmultilang ident="BANK_ACCOUNT_HOLDER" }]</label>
      <input type="text" size="20" class="js-oxValidate js-oxValidate_notEmpty" maxlength="50" name="CN" value="[{$oxcmp_user->oxuser__oxfname->value}] [{$oxcmp_user->oxuser__oxlname->value}]">
      <p class="oxValidateError">
        <span class="js-oxError_notEmpty">[{oxmultilang ident="ERROR_MESSAGE_INPUT_NOTALLFIELDS" }]</span>
      </p>
    </li>
    <li>
      <label>[{oxmultilang ident="VALID_UNTIL" }]</label>
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
      <label>[{oxmultilang ident="CARD_SECURITY_CODE" }]</label>
      <input type="text" class="js-oxValidate js-oxValidate_notEmpty" size="20" maxlength="6" name="CVC" value=""  autocomplete="off" />
      <p class="oxValidateError">
        <span class="js-oxError_notEmpty">[{oxmultilang ident="ERROR_MESSAGE_INPUT_NOTALLFIELDS" }]</span>
      </p>
      <br>
      <div class="note">[{oxmultilang ident="MO_INGENICO__PAGE_CHECKOUT_PAYMENT_SECURITYCODEDESCRIPTION" }]</div>
    </li>
  </div>
  [{/if}]
</ul>
