[{assign var=template_title value=mo_ingenico__payment_form}]

[{oxstyle include=$oViewConf->getModuleUrl('mo_ingenico','out/src/css/mo_ingenico.css')}]

[{capture append="oxidBlock_content"}]

    [{* ordering steps *}]
    [{*include file="page/checkout/inc/steps.tpl" active=4*}]

    <form id="mo_ingenico__form" action="[{$mo_ingenico__form_action}]" method="post">
        <h2>
            [{oxmultilang ident="MO_INGENICO__PAGE_PAYMENT_FORM_REDIRECT"}]
        </h2>
        <div class="form-group">
            <div class="col-lg-9">
                <button class="submitButton btn btn-primary" type="submit">
                    [{oxmultilang ident="MO_INGENICO__PAGE_PAYMENT_FORM_REDIRECT_BUTTON"}]
                </button>
            </div>
        </div>

        [{if $mo_ingenico__debug}]
        <div style="border: 2px solid red; font-size: 70%; padding: 10px; height: 300px; overflow: auto;">
            <strong style="display: block; background: black; color: white; padding: 5px; margin-bottom: 5px;">Debug-Ausgabe:</strong>
            [{/if}]

            [{foreach from=$mo_ingenico__hidden_fields key=name item=value}]
            [{if $mo_ingenico__debug}]
            <label style="display: block; font-weight: bold;">[{$name}]:
                <input type="text" name="[{$name}]" value="[{$value}]"
                       style="width: 100%; font-size: 80%; -moz-box-sizing: border-box;"/>
            </label>
            [{else}]
        <input type="hidden" name="[{$name}]" value="[{$value}]"/>
            [{/if}]
            [{/foreach}]

            [{* Submit form automatically via JS *}]
            [{if !$mo_ingenico__debug}]
            <script>
                window.onload = function () {
                    setTimeout(function () {
                        document.getElementById('mo_ingenico__form').submit();
                    }, 5000);
                };
            </script>
            [{/if}]

            [{if $mo_ingenico__debug}]
        </div>
        [{/if}]
    </form>

    [{insert name="oxid_tracker" title=$template_title}]
    [{/capture}]

[{include file="layout/page.tpl" sidebar=false blHideBreadcrumb=true}]