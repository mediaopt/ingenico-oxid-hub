[{* TODO: HK: clean update dynamic tpl content => base-tag not allowed by ogone *}]
[{* ToDo check if mobile is still needed! move to mobile folder?*}]
[{assign var="template_title" value="MO_OGONE_TEMPLATE_TITLE"|oxmultilangassign}]

[{oxstyle include=$oViewConf->getModuleUrl('mo_ogone','out/src/css/mo_ogone_mobile.css')}]

[{capture append="oxidBlock_content"}]

  [{* ordering steps *}]
  [{include file="page/checkout/inc/steps.tpl" active=4}]

  [{* Ogone placemark *}]
  $$$PAYMENT ZONE$$$

  [{insert name="oxid_tracker" title=$template_title}]
[{/capture}]

[{include file="layout/page.tpl"}]