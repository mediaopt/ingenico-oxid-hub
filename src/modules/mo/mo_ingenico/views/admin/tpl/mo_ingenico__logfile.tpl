[{include file="headitem.tpl" title="INGENICO_ADMIN_TITLE"|oxmultilangassign}]

[{if $readonly}]
  [{assign var="readonly" value="readonly disabled"}]
[{else}]
  [{assign var="readonly" value=""}]
[{/if}]
<form name="ingenicofilter" id="ingenicofilter" action="[{$oViewConf->getSelfLink()}]" method="post">
  [{$oViewConf->getHiddenSid()}]
  <input type="hidden" name="cl" value="mo_ingenico__logfile">
  <input type="hidden" name="fnc" value="downloadLogFile">
  <input type="submit" class="edittext" value="Download Logfile">
</form>
<div style="overflow:auto; display: block">
    <form name="ingenicofilter" id="ingenicofilter" action="[{$oViewConf->getSelfLink()}]" method="post">
      [{$oViewConf->getHiddenSid()}]
      <input type="hidden" name="cl" value="mo_ingenico__logfile">
      <input type="hidden" name="fnc" value="setFilter">

      <table>
        <tr class="listitem">
          <td class="listfilter">
            <input type="submit" class="edittext" value="Filter" style="width: 140px">
          </td>
          <td class="listfilter">
            <select name="ingenicologfilter[level]" type="select">
              <option></option>
              <option [{ if $ingenicologfilter.level eq 'DEBUG' }]selected[{ /if }]>DEBUG</option>
              <option [{ if $ingenicologfilter.level eq 'INFO' }]selected[{ /if }]>INFO</option>
              <option [{ if $ingenicologfilter.level eq 'ERROR' }]selected[{ /if }]>ERROR</option>
            </select>
          </td>
          <td class="listfilter">
            <input name="ingenicologfilter[message]" value="[{$ingenicologfilter.message}]" type="text" class="edittext">
          </td>
          <td class="listfilter">
            <input name="ingenicologfilter[context]" value="[{$ingenicologfilter.context}]" type="text" class="edittext">
          </td>
          <td class="listfilter">
            <input name="ingenicologfilter[extra]" value="[{$ingenicologfilter.extra}]" type="text" class="edittext">
          </td>
        </tr>
        <tr>
          <td class="listheader">[{oxmultilang ident="INGENICO_DATE" }]</td>
          <td class="listheader">[{oxmultilang ident="MO_INGENICO_LOGLEVEL" }]</td>
          <td class="listheader">[{oxmultilang ident="MO_INGENICO_MESSAGE" }]</td>
          <td class="listheader">[{oxmultilang ident="MO_INGENICO_CONTEXT" }]</td>
          <td class="listheader">[{oxmultilang ident="MO_INGENICO_EXTRA" }]</td>
        </tr>

        [{foreach from=$logfile item=oItem}]
        <tr class="ingenico_logentry">
          [{ if $oItem.date }]
            <td class="listitem">[{$oItem.date|date_format:"%d.%m.%y&nbsp;%H:%M:%S"}]</td>
            <td class="listitem">[{$oItem.level}]</td>
            <td class="listitem">
              <div class="mo_ingenico_logentry_shortened">[{$oItem.message|truncate:70:"...":true}]</div>
              <div class="mo_ingenico_logentry_full" style="display:none">[{$oItem.message}]</div>
            </td>
            <td class="listitem">
              <div class="mo_ingenico_logentry_shortened">[{$oItem.context|@debug_print_var|replace:"<br>":" "|truncate:100:"...":true}]</div>
              <div class="mo_ingenico_logentry_full" style="display:none">[{$oItem.context|@debug_print_var}]</div>
            </td>
            <td class="listitem">
              <div class="mo_ingenico_logentry_shortened">[{$oItem.extra|@debug_print_var|replace:"<br>":" "|truncate:100:"...":true}]</div>
              <div class="mo_ingenico_logentry_full" style="display:none">[{$oItem.extra|@debug_print_var}]</div>
            </td>
          [{ else }]
            <td class="listitem">
              <div class="mo_ingenico_logentry_shortened">[{$oItem.raw|truncate:100:"...":true}]</div>
              <div class="mo_ingenico_logentry_full" style="display:none">[{$oItem.extra|@debug_print_var}]</div>
            </td>
          [{ /if }]
        </tr>
        [{/foreach}]
      </table>
    </form>
</div>
<script type="application/javascript">
    document.querySelectorAll('.ingenico_logentry').forEach(function(obj) {
        obj.addEventListener('click', function (event) {
            event.currentTarget.querySelectorAll('.mo_ingenico_logentry_shortened, .mo_ingenico_logentry_full').forEach(function(element) {
                if (element.style.display === 'none') {
                    element.style.display = 'block';
                } else {
                    element.style.display = 'none';
                }
            });
        });
    });
</script>