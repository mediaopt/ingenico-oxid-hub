[{* TODO: HK: clean update dynamic tpl content => base-tag not allowed by ogone *}]
[{assign var="template_title" value="MO_OGONE_TEMPLATE_TITLE"|oxmultilangassign}]

[{oxstyle include="css/mo_ogone_mobile.css"}]

[{capture append="oxidBlock_content"}]

  [{* ordering steps *}]
  [{include file="page/checkout/inc/steps.tpl" active=4}]

  [{* Ogone placemark *}]
  $$$PAYMENT ZONE$$$

  [{insert name="oxid_tracker" title=$template_title}]
[{/capture}]

[{include file="layout/page.tpl"}]