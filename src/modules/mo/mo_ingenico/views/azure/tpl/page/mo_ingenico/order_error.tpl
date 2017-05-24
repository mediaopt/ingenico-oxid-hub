[{assign var=template_title value="MO_INGENICO__ORDER_ERROR_TEMPLATE_TITLE"|oxmultilangassign}]

[{oxstyle include=$oViewConf->getModuleUrl('mo_ingenico','out/src/css/mo_ingenico.css')}]

[{capture append="oxidBlock_content"}]

  [{$oView->mo_ingenico__getContent()}]

  [{insert name="oxid_tracker" title=$template_title}]
[{/capture}]

[{include file="layout/page.tpl"}]