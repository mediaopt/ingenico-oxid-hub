[{assign var="mo_ingenico__dynvalue" value=$oView->getDynValue()}]

<dl>
    <dt>
        <input id="payment_[{$sPaymentID}]" type="radio" name="paymentid" value="[{$sPaymentID}]"
               [{if $oView->getCheckedPaymentId() == $paymentmethod->oxpayments__oxid->value}]checked[{/if}]>
        <label for="payment_[{$sPaymentID}]"><b>[{$paymentmethod->oxpayments__oxdesc->value}]</b></label>
    </dt>
    <dd class="[{if $oView->getCheckedPaymentId() == $paymentmethod->oxpayments__oxid->value}]activePayment[{/if}]">
        <div class="form-group">
            <label class="req control-label col-lg-3">[{oxmultilang ident="BIRTHDATE"}]</label>
            <div class="col-lg-9">
                <input class="form-control js-oxValidate js-oxValidate_notEmpty"
                       type="text" size="20" maxlength="64" name="dynvalue[mo_ingenico][invoice][birthdate]"
                       value="[{$mo_ingenico__dynvalue.mo_ingenico.invoice.birthdate}]" required="required">
                <span class="help-block">[{oxmultilang ident="MO_INGENICO__BILLPAY_BIRTHDATE_HELP"}]</span>
            </div>
        </div>

        <div class="form-group">

            <div class="col-lg-9 col-lg-offset-3">
                <div class="checkbox">
                    <label>
                        <input type="checkbox"
                               name="dynvalue[mo_ingenico][invoice][agb]"
                               value="1"> [{oxmultilang ident="MO_INGENICO__I_HAVE" }]
                        <a style="text-decoration: underline;" target="_blank"
                           href="https://www.billpay.de/kunden/agb">[{oxmultilang ident="MO_INGENICO__BILLPAY_TERMS" }]</a>
                        [{oxmultilang ident="MO_INGENICO__READ_AND_ACCEPTED" }]
                    </label>
                </div>
            </div>
        </div>

    </dd>
</dl>
