<dl>
  <dt>
  <input id="payment_[{$sPaymentID}]" type="radio" name="paymentid" value="[{$sPaymentID}]" [{if $oView->getCheckedPaymentId() == $paymentmethod->oxpayments__oxid->value}]checked[{/if}]>
  <label for="payment_[{$sPaymentID}]"><b>[{ $paymentmethod->oxpayments__oxdesc->value}]</b></label>
  </dt>
  <dd class="[{if $oView->getCheckedPaymentId() == $paymentmethod->oxpayments__oxid->value}]activePayment[{/if}]">
    [{include file="mo_ogone__payment_creditcard_form_fields.tpl"}]
    [{assign var="oxConfig" value=$oView->getConfig()}]
    [{if $oxConfig->getShopConfVar('mo_ogone__use_hidden_auth')}]
        <input type="hidden" id="mo_ogone__paramvar" name="PARAMVAR" value="" />
        [{if $oxConfig->getShopConfVar('mo_ogone__use_iframe')}]
            [{$oView->mo_ogone__loadRequestParams('ogone_credit_card')}]
            
            <script type="text/javascript">
              (function () {
                var mo_ogone__iframeUrls = [{$oView->mo_ogone_getIFrameURLsAsJson('ogone_credit_card')}];
                  
                document.getElementById('mo_ogone__brand').onchange = function() {
                       document.getElementById('mo_ogone__iframe').src = mo_ogone__iframeUrls[this.value];
                }
                document.getElementById('mo_ogone__iframe').src = mo_ogone__iframeUrls[document.getElementById('mo_ogone__brand').value];
                document.getElementById('mo_ogone__paramvar').value = 'JS_ENABLED';
                
              })();
            </script>
        [{else}]
            [{$oView->mo_ogone__loadRequestParams('ogone_credit_card')}]

            [{foreach from=$oView->mo_ogone__getAliasGatewayParamsForJavascriptVersion() item="value" key="key"}]
              <input type="hidden" name="[{$key}]" value="[{$value}]" />
            [{/foreach}]
            <input type="hidden" id="mo_ogone__shasign" name="SHASIGN" value="" />


            <script type="text/javascript">
              (function () {
                var mo_ogone__shaSignatures = [{$oView->getAliasGatewayShaSignaturesAsJson()}];
                var mo_ogone__aliasGatewayUrl = '[{$oView->getAliasUrl()}]';

                function mo_ogone__setShaSignature()
                {
                  var brand = document.getElementById('mo_ogone__brand').value;
                  document.getElementById('mo_ogone__shasign').value = mo_ogone__shaSignatures[brand];
                }

                var oldWindowOnload = function () {};
                if (typeof window.onload != 'undefined') {
                  oldWindowOnload = window.onload;
                }

                window.onload = function () {
                  var form = document.getElementById('mo_ogone__shasign').form;
                  var radioButton = document.getElementById('payment_[{$sPaymentID}]');

                  document.getElementById('mo_ogone__paramvar').value = 'JS_ENABLED';

                  var oldOnSubmit = function () { return true };
                  if (typeof form.onsubmit != 'undefined') {
                    oldOnSubmit = form.onsubmit;
                  }

                  form.onsubmit = function () {
                    if (radioButton.checked) {
                      mo_ogone__setShaSignature();
                      this.action = mo_ogone__aliasGatewayUrl;
                    }

                    return oldOnSubmit();
                  };

                  oldWindowOnload();
                };
              })();
            </script>
        [{/if}]
    [{/if}]
  </dd>
</dl>

[{*

 <div class="well well-sm">
                                                                                    <dl>
    <dt>
        <input id="payment_oxidcreditcard" type="radio" name="paymentid" value="oxidcreditcard">
        <label for="payment_oxidcreditcard"><b>Kreditkarte</b></label>
    </dt>
    <dd class="" style="display: block;">

        <div class="form-group">
            <label class="req control-label col-lg-3">Karte</label>
            <div class="col-lg-9">
                <select name="dynvalue[kktype]" class="form-control selectpicker bs-select-hidden" required="required">
                    <option value="mcd" selected="">Mastercard</option>
                    <option value="vis">Visa</option>
                    <!--
                    <option value="amx" >American Express</option>
                    <option value="dsc" >Discover</option>
                    <option value="dnc" >Diners Club</option>
                    <option value="jcb" >JCB</option>
                    <option value="swi" >Switch</option>
                    <option value="dlt" >Delta</option>
                    <option value="enr" >EnRoute</option>
                    -->
                </select><div class="btn-group bootstrap-select form-control"><button type="button" class="btn dropdown-toggle btn-default" data-toggle="dropdown" title="Mastercard" aria-expanded="false"><span class="filter-option pull-left">Mastercard</span>&nbsp;<span class="caret"></span></button><div class="dropdown-menu open" style="max-height: 862px; overflow: hidden; min-height: 0px;"><ul class="dropdown-menu inner" role="menu" style="max-height: 850px; overflow-y: auto; min-height: 0px;"><li data-original-index="0" class="selected"><a tabindex="0" class="" style="" data-tokens="null"><span class="text">Mastercard</span><span class="glyphicon glyphicon-ok check-mark"></span></a></li><li data-original-index="1"><a tabindex="0" class="" style="" data-tokens="null"><span class="text">Visa</span><span class="glyphicon glyphicon-ok check-mark"></span></a></li></ul></div></div>
            </div>
        </div>

        <div class="form-group">
            <label class="req control-label col-lg-3">Nummer</label>
            <div class="col-lg-9">
                <input type="text" class="form-control js-oxValidate js-oxValidate_notEmpty" size="20" maxlength="64" name="dynvalue[kknumber]" value="" required="required">
            </div>
        </div>

        <div class="form-group">
            <label class="req control-label col-lg-3">Kontoinhaber</label>
            <div class="col-lg-9">
                <input type="text" size="20" class="form-control js-oxValidate js-oxValidate_notEmpty" maxlength="64" name="dynvalue[kkname]" value="Marc Muster" required="required">
                <span class="help-block">Falls abweichend von der Rechnungsadresse.</span>
            </div>
        </div>

        <div class="form-group">
            <label class="req control-label col-xs-12 col-lg-3">Gültig bis</label>
            <div class="col-xs-6 col-lg-2">
                <select name="dynvalue[kkmonth]" class="form-control selectpicker bs-select-hidden" required="required">
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
                </select><div class="btn-group bootstrap-select form-control"><button type="button" class="btn dropdown-toggle btn-default" data-toggle="dropdown" title="01"><span class="filter-option pull-left">01</span>&nbsp;<span class="caret"></span></button><div class="dropdown-menu open"><ul class="dropdown-menu inner" role="menu"><li data-original-index="0" class="selected"><a tabindex="0" class="" style="" data-tokens="null"><span class="text">01</span><span class="glyphicon glyphicon-ok check-mark"></span></a></li><li data-original-index="1"><a tabindex="0" class="" style="" data-tokens="null"><span class="text">02</span><span class="glyphicon glyphicon-ok check-mark"></span></a></li><li data-original-index="2"><a tabindex="0" class="" style="" data-tokens="null"><span class="text">03</span><span class="glyphicon glyphicon-ok check-mark"></span></a></li><li data-original-index="3"><a tabindex="0" class="" style="" data-tokens="null"><span class="text">04</span><span class="glyphicon glyphicon-ok check-mark"></span></a></li><li data-original-index="4"><a tabindex="0" class="" style="" data-tokens="null"><span class="text">05</span><span class="glyphicon glyphicon-ok check-mark"></span></a></li><li data-original-index="5"><a tabindex="0" class="" style="" data-tokens="null"><span class="text">06</span><span class="glyphicon glyphicon-ok check-mark"></span></a></li><li data-original-index="6"><a tabindex="0" class="" style="" data-tokens="null"><span class="text">07</span><span class="glyphicon glyphicon-ok check-mark"></span></a></li><li data-original-index="7"><a tabindex="0" class="" style="" data-tokens="null"><span class="text">08</span><span class="glyphicon glyphicon-ok check-mark"></span></a></li><li data-original-index="8"><a tabindex="0" class="" style="" data-tokens="null"><span class="text">09</span><span class="glyphicon glyphicon-ok check-mark"></span></a></li><li data-original-index="9"><a tabindex="0" class="" style="" data-tokens="null"><span class="text">10</span><span class="glyphicon glyphicon-ok check-mark"></span></a></li><li data-original-index="10"><a tabindex="0" class="" style="" data-tokens="null"><span class="text">11</span><span class="glyphicon glyphicon-ok check-mark"></span></a></li><li data-original-index="11"><a tabindex="0" class="" style="" data-tokens="null"><span class="text">12</span><span class="glyphicon glyphicon-ok check-mark"></span></a></li></ul></div></div>
            </div>
            <div class="col-xs-6 col-lg-2">
                <select name="dynvalue[kkyear]" class="form-control selectpicker bs-select-hidden">
                                            <option>2017</option>
                                            <option>2018</option>
                                            <option>2019</option>
                                            <option>2020</option>
                                            <option>2021</option>
                                            <option>2022</option>
                                            <option>2023</option>
                                            <option>2024</option>
                                            <option>2025</option>
                                            <option>2026</option>
                                            <option>2027</option>
                                    </select><div class="btn-group bootstrap-select form-control"><button type="button" class="btn dropdown-toggle btn-default" data-toggle="dropdown" title="2017"><span class="filter-option pull-left">2017</span>&nbsp;<span class="caret"></span></button><div class="dropdown-menu open"><ul class="dropdown-menu inner" role="menu"><li data-original-index="0" class="selected"><a tabindex="0" class="" style="" data-tokens="null"><span class="text">2017</span><span class="glyphicon glyphicon-ok check-mark"></span></a></li><li data-original-index="1"><a tabindex="0" class="" style="" data-tokens="null"><span class="text">2018</span><span class="glyphicon glyphicon-ok check-mark"></span></a></li><li data-original-index="2"><a tabindex="0" class="" style="" data-tokens="null"><span class="text">2019</span><span class="glyphicon glyphicon-ok check-mark"></span></a></li><li data-original-index="3"><a tabindex="0" class="" style="" data-tokens="null"><span class="text">2020</span><span class="glyphicon glyphicon-ok check-mark"></span></a></li><li data-original-index="4"><a tabindex="0" class="" style="" data-tokens="null"><span class="text">2021</span><span class="glyphicon glyphicon-ok check-mark"></span></a></li><li data-original-index="5"><a tabindex="0" class="" style="" data-tokens="null"><span class="text">2022</span><span class="glyphicon glyphicon-ok check-mark"></span></a></li><li data-original-index="6"><a tabindex="0" class="" style="" data-tokens="null"><span class="text">2023</span><span class="glyphicon glyphicon-ok check-mark"></span></a></li><li data-original-index="7"><a tabindex="0" class="" style="" data-tokens="null"><span class="text">2024</span><span class="glyphicon glyphicon-ok check-mark"></span></a></li><li data-original-index="8"><a tabindex="0" class="" style="" data-tokens="null"><span class="text">2025</span><span class="glyphicon glyphicon-ok check-mark"></span></a></li><li data-original-index="9"><a tabindex="0" class="" style="" data-tokens="null"><span class="text">2026</span><span class="glyphicon glyphicon-ok check-mark"></span></a></li><li data-original-index="10"><a tabindex="0" class="" style="" data-tokens="null"><span class="text">2027</span><span class="glyphicon glyphicon-ok check-mark"></span></a></li></ul></div></div>
            </div>
            <div class="col-sm-3"></div>
        </div>

        <div class="form-group">
            <label class="req control-label col-lg-3">Prüfziffer</label>
            <div class="col-lg-9">
                <input type="text" class="form-control js-oxValidate js-oxValidate_notEmpty" size="20" maxlength="64" name="dynvalue[kkpruef]" value="" required="required">
                <span class="help-block">Diese befindet sich auf der Rückseite Ihrer Kreditkarte. Die Prüfziffer sind die letzten drei Ziffern im Unterschriftsfeld.</span>
            </div>
        </div>

        <div class="clearfix"></div>


                            <div class="alert alert-info col-lg-offset-3 desc">
                    Die Belastung Ihrer Kreditkarte erfolgt mit dem Abschluss der Bestellung.
                </div>

    </dd>
</dl>                                                                            </div>

 *}]