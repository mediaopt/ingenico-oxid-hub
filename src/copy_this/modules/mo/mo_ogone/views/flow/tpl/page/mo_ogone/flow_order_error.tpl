[{assign var=template_title value="MO_OGONE__ORDER_ERROR_TEMPLATE_TITLE"|oxmultilangassign}]

[{oxstyle include=$oViewConf->getModuleUrl('mo_ogone','out/src/css/mo_ogone.css')}]

[{capture append="oxidBlock_content"}]

  [{$oView->mo_ogone__getContent()}]

  [{insert name="oxid_tracker" title=$template_title}]
[{/capture}]

[{include file="layout/page.tpl"}]