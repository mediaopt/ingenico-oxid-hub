[{* TODO: HK: clean update dynamic tpl content => base-tag not allowed by ingenico *}]
[{* ToDo check if mobile is still needed! move to mobile folder?*}]
[{assign var="template_title" value="MO_INGENICO_TEMPLATE_TITLE"|oxmultilangassign}]

[{oxstyle include=$oViewConf->getModuleUrl('mo_ingenico','out/src/css/mo_ingenico_mobile.css')}]

[{capture append="oxidBlock_content"}]

  [{* ordering steps *}]
  [{include file="page/checkout/inc/steps.tpl" active=4}]

  [{* Ingenico placemark *}]
  $$$PAYMENT ZONE$$$

  [{insert name="oxid_tracker" title=$template_title}]
[{/capture}]

[{include file="layout/page.tpl"}]