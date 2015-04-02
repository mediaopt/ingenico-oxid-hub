[{include file="headitem.tpl" title="OGONE_SETUP_TITLE"|oxmultilangassign}]


[{if $readonly}]
  [{assign var="readonly" value="readonly disabled"}]
[{else}]
  [{assign var="readonly" value=""}]
[{/if}]

[{cycle assign="_clear_" values=",2" }]

<div id=liste>

  [{if $mo_ogone__sqlExecutionErrors}]
    <strong style="color: red">[{oxmultilang ident="MO_OGONE__SQL_INSTALL_ERROR_HEADER"}]</strong>
    <pre style="border: 2px solid red;">[{$mo_ogone__sqlExecutionErrors}]</pre>
  [{/if}]

  <form name="myedit" id="myedit" action="[{$oViewConf->getSelfLink()}]" method="post">
    [{$oViewConf->getHiddenSid()}]
    <input type="hidden" name="cl" value="[{$oViewConf->getActiveClassName()}]">
    <input type="hidden" name="fnc" value="save">
    <input type="hidden" name="oxid" value="[{$oxid}]">
    <input type="hidden" name="editval[oxshops__oxid]" value="[{$oxid}]">
    <table cellspacing="0" cellpadding="0" border="0" style="width:100%;height:100%;">

      <tr>
        <td valign="top" class="edittext" style="padding:10px;">
          <table cellspacing="0" cellpadding="5" border="0" class="edittext" style="text-align: left;">
            <tr>
              <td valign="top" class="edittext" width="250" nowrap="">[{ oxmultilang ident="OGONE_PAYMENT_METHODS" }]</td>
              <td valign="top" class="edittext">
                [{foreach from=$aPaymentMethods item=pm}]
                  <input type="checkbox" name="ogone_aPaymentMethods[]" value=[{$pm.id}][{ if $pm.checked}] checked="checked"[{ /if }]> [{ oxmultilang ident=$pm.name }]<br />

                  [{* check for payment options *}]
                  [{assign var="mo_ogone__paymentOptions" value=$oView->mo_ogone__getMultiplePaymentOptionsByOxidPaymentId($pm.id)}]
                  [{if $mo_ogone__paymentOptions}]
                    [{foreach from=$mo_ogone__paymentOptions item="mo_ogone__paymentOption"}]
                      <input  style="margin-left: 20px;" 
                              type="checkbox" 
                              name="mo_ogone__paymentOptions[[{$pm.id}]][]" 
                      [{if $mo_ogone__paymentOption.active}]checked="checked"[{/if}]
                      value="[{$mo_ogone__paymentOption.brand}]"  /> [{$mo_ogone__paymentOption.brand}]<br />
                  [{/foreach}]
                [{/if}]

              [{/foreach}]
            </td>
          </tr>
          <tr>
            <td valign="top" class="edittext" width="250" nowrap="">[{ oxmultilang ident="MO_OGONE__ISLIVEMODE" }]</td>
            <td valign="top" class="edittext">
              <input type="hidden" name="confbools[mo_ogone__isLiveMode]" value="false" />
              <input type="checkbox" 
                     class="editinput" 
                     name="confbools[mo_ogone__isLiveMode]" 
                     value="true" 
              [{if $confbools.mo_ogone__isLiveMode}]checked="checked"[{/if}] />
          </td>
        </tr>
        <tr>
          <td valign="top" class="edittext" width="250" nowrap="">[{ oxmultilang ident="MO_OGONE_LOGLEVEL" }]</td>
          <td valign="top" class="edittext">
            <select name="confstrs[mo_ogone__logLevel]" class="editinput" [{ $readonly }]>
              <option value="DEBUG" [{ if $confstrs.mo_ogone__logLevel == "DEBUG" }]SELECTED[{/if}]>[{ oxmultilang ident="MO_OGONE_LOGLEVEL_DEBUG" }]</option>
              <option value="INFO" [{ if $confstrs.mo_ogone__logLevel == "INFO" }]SELECTED[{/if}]>[{ oxmultilang ident="MO_OGONE_LOGLEVEL_INFO" }]</option>
              <option value="ERROR" [{ if $confstrs.mo_ogone__logLevel == "ERROR"  || $confstrs.ogone_sHashingAlgorithm == ""}]SELECTED[{/if}]>[{ oxmultilang ident="MO_OGONE_LOGLEVEL_ERROR" }]</option>
            </select>
          </td>
        </tr>
          <tr>
            <td valign="top" class="edittext" width="250" nowrap="">[{ oxmultilang ident="MO_OGONE__CAPTURE_CREDITCARD" }]</td>
            <td valign="top" class="edittext">
              <input type="hidden" name="confbools[mo_ogone__capture_creditcard]" value="false" />
              <input type="checkbox" 
                     class="editinput" 
                     name="confbools[mo_ogone__capture_creditcard]" 
                     value="true" 
              [{if $confbools.mo_ogone__capture_creditcard}]checked="checked"[{/if}] />
            [{ oxinputhelp ident="MO_OGONE__CAPTURE_CREDITCARD_HELP" }]
          </td>
        </tr>
                  <tr>
            <td valign="top" class="edittext" width="250" nowrap="">[{ oxmultilang ident="MO_OGONE__USE_UTF8" }]</td>
            <td valign="top" class="edittext">
              <input type="hidden" name="confbools[mo_ogone__use_utf8]" value="false" />
              <input type="checkbox" 
                     class="editinput" 
                     name="confbools[mo_ogone__use_utf8]" 
                     value="true" 
              [{if $confbools.mo_ogone__use_utf8}]checked="checked"[{/if}] />
            [{ oxinputhelp ident="MO_OGONE__USE_UTF8_HELP" }]
          </td>
        </tr>
          <tr>
            <td valign="top" class="edittext" width="250" nowrap="">[{ oxmultilang ident="MO_OGONE__SET_OXPAID" }]</td>
            <td valign="top" class="edittext">
              <input type="hidden" name="confbools[mo_ogone__set_oxpaid]" value="false" />
              <input type="checkbox" 
                     class="editinput" 
                     name="confbools[mo_ogone__set_oxpaid]" 
                     value="true" 
              [{if $confbools.mo_ogone__set_oxpaid}]checked="checked"[{/if}] />
            [{ oxinputhelp ident="MO_OGONE__SET_OXPAID_HELP" }]
          </td>
        </tr>

        <tr>
            <td valign="top" class="edittext" width="250" nowrap="">[{ oxmultilang ident="MO_OGONE__USE_HIDDEN_AUTH" }]</td>
            <td valign="top" class="edittext">
              <input type="hidden" name="confbools[mo_ogone__use_hidden_auth]" value="false" />
              <input type="checkbox"
                     class="editinput"
                     name="confbools[mo_ogone__use_hidden_auth]"
                     value="true"
              [{if $confbools.mo_ogone__use_hidden_auth}]checked="checked"[{/if}] />
            [{ oxinputhelp ident="MO_OGONE__USE_HIDDEN_AUTH_HELP" }]
          </td>
        </tr>
        
        <tr>
            <td valign="top" class="edittext" width="250" nowrap="">[{ oxmultilang ident="MO_OGONE__USE_IFRAME_FOR_HIDDEN_AUTH" }]</td>
            <td valign="top" class="edittext">
              <input type="hidden" name="confbools[mo_ogone__use_iframe]" value="false" />
              <input type="checkbox"
                     class="editinput"
                     name="confbools[mo_ogone__use_iframe]"
                     value="true"
              [{if $confbools.mo_ogone__use_iframe}]checked="checked"[{/if}] />
            [{ oxinputhelp ident="MO_OGONE__USE_IFRAME_FOR_HIDDEN_AUTH_HELP" }]
          </td>
        </tr>
        
        <tr>
          <td valign="top" class="edittext" width="250" nowrap="">[{ oxmultilang ident="OGONE_PSPID" }]</td>
          <td valign="top" class="edittext">
            <input type=text class="editinput" style="width:410px" name=confstrs[ogone_sPSPID] value="[{$confstrs.ogone_sPSPID}]" maxlength="30" />
            [{ oxinputhelp ident="HELP_OGONE_PSPID" }]
          </td>
        </tr>

        <tr>
          <td valign="top" class="edittext" width="250" nowrap="">[{ oxmultilang ident="MO_OGONE__API_USERID" }]</td>
          <td valign="top" class="edittext">
            <input type=text class="editinput" style="width:410px" name=confstrs[mo_ogone__api_userid] value="[{$confstrs.mo_ogone__api_userid}]" maxlength="30" />
            [{ oxinputhelp ident="MO_OGONE__API_USERID_HELP" }]
          </td>
        </tr>
        <tr>
          <td valign="top" class="edittext" width="250" nowrap="">[{ oxmultilang ident="MO_OGONE__API_USERPASS" }]</td>
          <td valign="top" class="edittext">
            <input type="password" class="editinput" style="width:410px" name="confstrs[mo_ogone__api_userpass]" value="[{$confstrs.mo_ogone__api_userpass}]" maxlength="30" />
            [{ oxinputhelp ident="MO_OGONE__API_USERPASS_HELP" }]
          </td>
        </tr>

        <tr>
          <td valign="top" class="edittext" width="250" nowrap="">[{ oxmultilang ident="OGONE_HASHING" }]</td>
          <td valign="top" class="edittext">
            <select name="confstrs[ogone_sHashingAlgorithm]" class="editinput" [{ $readonly }]>
              <option value="SHA-1" [{ if $confstrs.ogone_sHashingAlgorithm == "SHA-1" }]SELECTED[{/if}]>[{ oxmultilang ident="OGONE_HASHING_ALGORITHM_SHA_1" }]</option>
              <option value="SHA-256" [{ if $confstrs.ogone_sHashingAlgorithm == "SHA-256" || $confstrs.ogone_sHashingAlgorithm == "" }]SELECTED[{/if}]>[{ oxmultilang ident="OGONE_HASHING_ALGORITHM_SHA_256" }]</option>
              <option value="SHA-512" [{ if $confstrs.ogone_sHashingAlgorithm == "SHA-512" }]SELECTED[{/if}]>[{ oxmultilang ident="OGONE_HASHING_ALGORITHM_SHA_512" }]</option>
            </select>
          </td>
        </tr>

        <tr>
          <td valign="top" class="edittext" width="250" nowrap="">[{ oxmultilang ident="OGONE_SECURE_KEY_IN" }]</td>
          <td valign="top" class="edittext">
            <input type=password class="editinput" style="width:410px;" name=confstrs[ogone_sSecureKeyIn] value="[{$confstrs.ogone_sSecureKeyIn}]"><br />
          </td>
        </tr>
        <tr>
          <td valign="top" class="edittext" width="250" nowrap="">[{ oxmultilang ident="OGONE_SECURE_KEY_OUT" }]</td>
          <td valign="top" class="edittext">
            <input type=password class="editinput" style="width:410px;" name=confstrs[ogone_sSecureKeyOut] value="[{$confstrs.ogone_sSecureKeyOut}]"><br />
          </td>
        </tr>

        <!--
        <tr>
          <td valign="top" class="edittext" width="250" nowrap="">[{ oxmultilang ident="OGONE_ALIAS" }]</td>
          <td valign="top" class="edittext" nowrap="">
            <fieldset id="ogonetemplate">
              <legend>
                <input type=hidden name=confbools[ogone_blAlias] value=false>
                <input type="checkbox" name=confbools[ogone_blAlias] value="true"[{ if $confbools.ogone_blAlias == true }] checked=""[{/if}] onchange="Javascript:templateAlias(this);" [{ $readonly }]>
              </legend>
        [{ oxmultilang ident="OGONE_ALIAS_USAGE" }]<br />
        <span id="alias_usage" style="display:inline;">
        [{foreach from=$languages item=lang}]
          [{ $lang->name }] <input type=text class="editinput" style="width:410px;" name=confstrs[ogone_sAliasUsage[{ $lang->id }]] value="[{ $lang->aliasUsage }]"><br />
        [{/foreach}]
        </span>
      </fieldset>
    </td>
  </tr>
        -->
        <tr>
          <td valign="top" class="edittext" width="250" nowrap="">[{ oxmultilang ident="OGONE_TEMPLATE" }]</td>
          <td valign="top" class="edittext">
            <fieldset id="ogonetemplate">
              <legend>
                <select name=confstrs[ogone_sTemplate] class="editinput" onchange="Javascript:templateOptions(this);" [{ $readonly }]>
                  <option value="true" [{ if $confstrs.ogone_sTemplate == "true" }]SELECTED[{/if}]>[{ oxmultilang ident="OGONE_TEMPLATE_TRUE" }]</option>
                  <option value="false" [{ if $confstrs.ogone_sTemplate == "false" }]SELECTED[{/if}]>[{ oxmultilang ident="OGONE_TEMPLATE_FALSE" }]</option>
                </select>
              </legend>
              <table id="template_styling" cellspacing="0" cellpadding="5" border="0" class="edittext" style="text-align: left;">
                <tr>
                  <td valign="top" class="edittext" nowrap="">[{ oxmultilang ident="OGONE_PMLISTSTYLE_TITLE" }]</td>
                  <td valign="top" class="edittext" nowrap="">
                    <select name="confstrs[ogone_sTplPMListStyle]" class="editinput" [{ $readonly }]>
                      <option value="0" [{ if $confstrs.ogone_sTplPMListStyle == 0 }]SELECTED[{/if}]>[{ oxmultilang ident="OGONE_PMLISTSTYLE_0" }]</option>
                      <option value="1" [{ if $confstrs.ogone_sTplPMListStyle == 1 }]SELECTED[{/if}]>[{ oxmultilang ident="OGONE_PMLISTSTYLE_1" }]</option>
                      <option value="2" [{ if $confstrs.ogone_sTplPMListStyle == 2 }]SELECTED[{/if}]>[{ oxmultilang ident="OGONE_PMLISTSTYLE_2" }]</option>
                    </select>
                    [{ oxinputhelp ident="OGONE_PMLISTSTYLE_TITLE_DESCRIPTION" }]
                  </td>
                </tr>
                <tr>
                  <td valign="top" class="edittext" nowrap="">[{ oxmultilang ident="OGONE_BACK_TITLE" }]</td>
                  <td valign="top" class="edittext" nowrap="">
                    <input type=hidden name=confbools[ogone_blBackButton] value=false>
                    <input type="checkbox" name=confbools[ogone_blBackButton] value="true"[{ if $confbools.ogone_blBackButton == 'true' }] checked[{/if}] [{ $readonly }]>
                  </td>
                </tr>
              </table>

              <table id="template_dynamic" cellspacing="0" cellpadding="5" border="0" class="edittext" style="text-align: left;">
              </table>

              <table id="template_static" cellspacing="0" cellpadding="5" border="0" class="edittext" style="text-align: left;">
                <tr>
                  <td valign="top" class="edittext" nowrap="">[{ oxmultilang ident="OGONE_TEMPLATE_TITLE" }]</td>
                  <td valign="top" class="edittext" nowrap="">
                    <input type="checkbox" name=confbools[ogone_blTplTitle] value="true"[{ if $confbools.ogone_blTplTitle == 'true' }] checked[{/if}] onchange="Javascript:templateTitle(this);" [{ $readonly }]>
                    [{ oxinputhelp ident="OGONE_TEMPLATE_TITLE_DESCRIPTION" }]
                    <br />
                    <span id="template_title">
                      [{foreach from=$languages item=lang}]
                        [{ $lang->name }] <input type=text class="editinput" style="width:410px;" name=confstrs[ogone_sTplTitle[{ $lang->id }]] value="[{ $lang->tplTitle }]"><br />
                      [{/foreach}]
                    </span>
                  </td>
                </tr>
                <tr>
                  <td valign="top" class="edittext" nowrap="">[{ oxmultilang ident="OGONE_TEMPLATE_BGCOLOR" }]</td>
                  <td valign="top" class="edittext" nowrap="">
                    <input type=text class="editinput" style="width:250px;" name=confstrs[ogone_sTplBGColor] value="[{$confstrs.ogone_sTplBGColor}]">
                    [{ oxinputhelp ident="OGONE_TEMPLATE_BGCOLOR_DESCRIPTION" }]
                  </td>
                </tr>
                <tr>
                  <td valign="top" class="edittext" nowrap="">[{ oxmultilang ident="OGONE_TEMPLATE_FONTCOLOR" }]</td>
                  <td valign="top" class="edittext" nowrap="">
                    <input type=text class="editinput" style="width:250px;" name=confstrs[ogone_sTplFontColor] value="[{$confstrs.ogone_sTplFontColor}]">
                    [{ oxinputhelp ident="OGONE_TEMPLATE_FONTCOLOR_DESCRIPTION" }]
                  </td>
                </tr>
                <tr>
                  <td valign="top" class="edittext" nowrap="">[{ oxmultilang ident="OGONE_TEMPLATE_TABLE_BGCOLOR" }]</td>
                  <td valign="top" class="edittext" nowrap="">
                    <input type=text class="editinput" style="width:250px;" name=confstrs[ogone_sTplTableBGColor] value="[{$confstrs.ogone_sTplTableBGColor}]">
                    [{ oxinputhelp ident="OGONE_TEMPLATE_TABLE_BGCOLOR_DESCRIPTION" }]
                  </td>
                </tr>
                <tr>
                  <td valign="top" class="edittext" nowrap="">[{ oxmultilang ident="OGONE_TEMPLATE_TABLE_FONTCOLOR" }]</td>
                  <td valign="top" class="edittext" nowrap="">
                    <input type=text class="editinput" style="width:250px;" name=confstrs[ogone_sTplTbFontColor] value="[{$confstrs.ogone_sTplTbFontColor}]">
                    [{ oxinputhelp ident="OGONE_TEMPLATE_TABLE_FONTCOLOR_DESCRIPTION" }]
                  </td>
                </tr>
                <tr>
                  <td valign="top" class="edittext" nowrap="">[{ oxmultilang ident="OGONE_TEMPLATE_BUTTON_BGCOLOR" }]</td>
                  <td valign="top" class="edittext" nowrap="">
                    <input type=text class="editinput" style="width:250px;" name=confstrs[ogone_sTplBtnBGColor] value="[{$confstrs.ogone_sTplBtnBGColor}]">
                    [{ oxinputhelp ident="OGONE_TEMPLATE_BUTTON_BGCOLOR_DESCRIPTION" }]
                  </td>
                </tr>
                <tr>
                  <td valign="top" class="edittext" nowrap="">[{ oxmultilang ident="OGONE_TEMPLATE_BUTTON_FONTCOLOR" }]</td>
                  <td valign="top" class="edittext" nowrap="">
                    <input type=text class="editinput" style="width:250px;" name=confstrs[ogone_sTplBtnFontColor] value="[{$confstrs.ogone_sTplBtnFontColor}]">
                    [{ oxinputhelp ident="OGONE_TEMPLATE_BUTTON_FONTCOLOR_DESCRIPTION" }]
                  </td>
                </tr>
                <tr>
                  <td valign="top" class="edittext" nowrap="">[{ oxmultilang ident="OGONE_TEMPLATE_FONTFAMILY" }]</td>
                  <td valign="top" class="edittext" nowrap="">
                    <input type=text class="editinput" style="width:250px;" name=confstrs[ogone_sTplFontFamily] value="[{$confstrs.ogone_sTplFontFamily}]">
                    [{ oxinputhelp ident="OGONE_TEMPLATE_FONTFAMILY_DESCRIPTION" }]
                  </td>
                </tr>
                <tr>
                  <td valign="top" class="edittext" nowrap="">[{ oxmultilang ident="OGONE_TEMPLATE_LOGO" }]</td>
                  <td valign="top" class="edittext" nowrap="">
                    <input type=text class="editinput" style="width:250px;" name=confstrs[ogone_sTplLogo] value="[{$confstrs.ogone_sTplLogo}]">
                    [{ oxinputhelp ident="OGONE_TEMPLATE_LOGO_DESCRIPTION" }]
                  </td>
                </tr>
              </table>
            </fieldset>
            <br />
            [{ if $start_setup }]
              <input type="submit" name="save" value="[{ oxmultilang ident="START_SETUP" }]" [{ $readonly}]>
            [{else}]
              <input type="submit" name="save" value="[{ oxmultilang ident="UPDATE_SETUP" }]" [{ $readonly}]>

              [{*if $oView->mo_ogone__hasRegisteredTemplateBlocks()}]
              <input type="button" value="[{ oxmultilang ident="MO_OGONE__UNINSTALL_TPL_BLOCKS" }]" [{ $readonly}] onclick="window.location.href='[{$oViewConf->getSelfLink()}]cl=mo_ogone__setup&fnc=mo_ogone__fncUninstallTemplateBlocks';" />
              [{else}]
              <input type="button" value="[{ oxmultilang ident="MO_OGONE__INSTALL_TPL_BLOCKS" }]" [{ $readonly}] onclick="window.location.href='[{$oViewConf->getSelfLink()}]cl=mo_ogone__setup&fnc=mo_ogone__fncInstallTemplateBlocks';" />
              [{/if*}]

            [{/if}]
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
</form>
<div style="text-align:right; font-weight:bold;">Ogone Module Version: [{$mo_ogone__moduleVersion}]</div>
[{include file="pagenavisnippet.tpl"}]
</div>
[{include file="pagetabsnippet.tpl"}]

[{include file="bottomnaviitem.tpl"}]

[{include file="bottomitem.tpl"}]
