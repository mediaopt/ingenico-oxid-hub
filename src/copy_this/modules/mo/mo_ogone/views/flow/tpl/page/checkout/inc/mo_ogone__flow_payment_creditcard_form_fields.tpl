[{assign var="oxConfig" value=$oView->getConfig()}]


[{if !$mo_ogone__brand || !$oxConfig->getShopConfVar('mo_ogone__use_hidden_auth')}]
  [{* JS-Version *}]

  <div class="form-group">
    <label class="req control-label col-lg-3">[{oxmultilang ident="CREDITCARD"}]</label>
    <div class="col-lg-9">
      [{if $oxConfig->getShopConfVar('mo_ogone__use_hidden_auth')}]

      [{if $oxConfig->getShopConfVar('mo_ogone__use_iframe')}]
      <select name="CARD.BRAND" id="mo_ogone__brand" class="form-control selectpicker"
              required="required">
        [{else}]
        <select name="BRAND" id="mo_ogone__brand" class="form-control selectpicker"
                required="required">
          [{/if}]

          [{else}]
          <select name="dynvalue[mo_ogone][cc][brand]" id="mo_ogone__brand" class="form-control selectpicker"
                  required="required">
            [{/if}]
            [{assign var="mo_ogone__currentPaymentConfig" value=$oView->mo_ogone__getCurrentPaymentConfig('ogone_credit_card')}]

            [{foreach from=$mo_ogone__currentPaymentConfig.brand item="brand"}]
            [{if $oxConfig->getShopConfVar('mo_ogone__use_hidden_auth') && $oxConfig->getShopConfVar('mo_ogone__use_iframe') && $brand == 'MasterCard'}]
            <option value="Eurocard">[{$brand}]</option>
          [{ else }]
            <option value="[{$brand}]">[{$brand}]</option>
            [{/if}]
            [{/foreach}]
          </select>
    </div>
  </div>

  [{else}]
  [{* Non JS-Version *}]
  <div class="form-group">
    <label class="req control-label col-lg-3">[{oxmultilang ident="CREDITCARD" }]: <strong>[{$mo_ogone__brand}]</strong></label>
  </div>
  [{/if}]


[{if $oxConfig->getShopConfVar('mo_ogone__use_hidden_auth') && $oxConfig->getShopConfVar('mo_ogone__use_iframe')}]
  <div class="form-group">
    <div class="col-lg-9 col-lg-offset-3">
      <iframe id="mo_ogone__iframe" src="" width="350px" height="330px">
      </iframe>
    </div>
  </div>
  [{/if}]


[{* Hidden Auth using form on shopsite (not via iFrame) *}]
[{if $oxConfig->getShopConfVar('mo_ogone__use_hidden_auth') && !$oxConfig->getShopConfVar('mo_ogone__use_iframe')}]
  <div class="form-group">
    <label class="req control-label col-lg-3">[{oxmultilang ident="NUMBER"}]</label>
    <div class="col-lg-9">
      <input type="text" class="form-control js-oxValidate js-oxValidate_notEmpty" size="20" maxlength="64"
             name="CardNo" value="" required="required" autocomplete="off">
    </div>
  </div>

  <div class="form-group">
    <label class="req control-label col-lg-3">[{oxmultilang ident="BANK_ACCOUNT_HOLDER"}]</label>
    <div class="col-lg-9">
      <input type="text" size="20" class="form-control js-oxValidate js-oxValidate_notEmpty" maxlength="64"
             name="CN" value="[{$oxcmp_user->oxuser__oxfname->value}] [{$oxcmp_user->oxuser__oxlname->value}]"
             required="required">
    </div>
  </div>

  <div class="form-group">
    <label class="req control-label col-xs-12 col-lg-3">[{oxmultilang ident="VALID_UNTIL"}]</label>
    <div class="col-xs-6 col-lg-2">
      <select name="ECOM_CARDINFO_EXPDATE_MONTH" class="form-control selectpicker" required="required">
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
    </div>
    <div class="col-xs-6 col-lg-2">
      <select name="ECOM_CARDINFO_EXPDATE_YEAR" class="form-control selectpicker">
        [{foreach from=$oView->getCreditYears() item=year}]
        <option>[{$year}]</option>
        [{/foreach}]
      </select>
    </div>
    <div class="col-sm-3"></div>
  </div>

  <div class="form-group">
    <label class="req control-label col-lg-3">[{oxmultilang ident="CARD_SECURITY_CODE"}]</label>
    <div class="col-lg-9">
      <input type="text" class="form-control js-oxValidate js-oxValidate_notEmpty" size="20" maxlength="64"
             name="CVC" value="" required="required" autocomplete="off">
      <span class="help-block">[{oxmultilang ident="MO_OGONE__PAGE_CHECKOUT_PAYMENT_SECURITYCODEDESCRIPTION"}]</span>
    </div>
  </div>
  [{/if}]
