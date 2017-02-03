[{assign var="mo_ogone__dynvalue" value=$oView->getDynValue()}]

<dl>
    <dt>
        <input id="payment_[{$sPaymentID}]" type="radio" name="paymentid" value="[{$sPaymentID}]"
               [{if $oView->getCheckedPaymentId() == $paymentmethod->oxpayments__oxid->value}]checked[{/if}]>
        <label for="payment_[{$sPaymentID}]"><b>[{ $paymentmethod->oxpayments__oxdesc->value}]</b></label>
    </dt>
    <dd class="[{if $oView->getCheckedPaymentId() == $paymentmethod->oxpayments__oxid->value}]activePayment[{/if}]">
        <div class="form-group">
            <label class="req control-label col-lg-3">[{oxmultilang ident="BIRTHDATE"}]</label>
            <div class="col-lg-9">
                <input class="form-control js-oxValidate js-oxValidate_notEmpty"
                       type="text" size="20" maxlength="64" name="dynvalue[mo_ogone][invoice][birthdate]"
                       value="[{$mo_ogone__dynvalue.mo_ogone.invoice.birthdate}]" required="required">
                <div class="alert alert-info col-lg-offset-3 desc">(dd.mm.YYYY)</div>
            </div>
        </div>
        <div class="form-group">

            <div class="col-lg-9">
                <div class="checkbox">
                    <label class="req control-label col-lg-3">[{ oxmultilang ident="MO_OGONE__I_HAVE" }]
                        <a style="text-decoration: underline;" target="_blank"
                           href="https://www.billpay.de/kunden/agb">[{oxmultilang ident="MO_OGONE__BILLPAY_TERMS" }]</a>
                        [{oxmultilang ident="MO_OGONE__READ_AND_ACCEPTED" }]
                        <input type="hidden" name="dynvalue[mo_ogone][invoice][agb]" value="0">
                        <input type="checkbox"
                               name="dynvalue[mo_ogone][invoice][agb]"
                               value="1"/>
                    </label>
                </div>
            </div>
        </div>
    </dd>
</dl>
