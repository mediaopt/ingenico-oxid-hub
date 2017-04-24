[{include file="headitem.tpl" title="OGONE_ADMIN_TITLE"|oxmultilangassign}]

[{if $readonly}]
  [{assign var="readonly" value="readonly disabled"}]
[{else}]
  [{assign var="readonly" value=""}]
[{/if}]

<div style="overflow:auto; display: block">
    <form name="ogonefilter" id="ogonefilter" action="[{$oViewConf->getSelfLink()}]" method="post">
      [{$oViewConf->getHiddenSid()}]
      <input type="hidden" name="cl" value="mo_ogone__logfile">
      <input type="hidden" name="fnc" value="setFilter">

      <table>
        <tr class="listitem">
          <td class="listfilter">
            <input type="submit" class="edittext" value="Filter" style="width: 140px">
          </td>
          <td class="listfilter">
            <select name="ogonelogfilter[level]" type="select">
              <option></option>
              <option [{ if $ogonelogfilter.level eq 'DEBUG' }]selected[{ /if }]>DEBUG</option>
              <option [{ if $ogonelogfilter.level eq 'INFO' }]selected[{ /if }]>INFO</option>
              <option [{ if $ogonelogfilter.level eq 'ERROR' }]selected[{ /if }]>ERROR</option>
            </select>
          </td>
          <td class="listfilter">
            <input name="ogonelogfilter[message]" value="[{$ogonelogfilter.message}]" type="text" class="edittext">
          </td>
          <td class="listfilter">
            <input name="ogonelogfilter[context]" value="[{$ogonelogfilter.context}]" type="text" class="edittext">
          </td>
          <td class="listfilter">
            <input name="ogonelogfilter[extra]" value="[{$ogonelogfilter.extra}]" type="text" class="edittext">
          </td>
        </tr>
        <tr>
          <td class="listheader">[{oxmultilang ident="OGONE_DATE" }]</td>
          <td class="listheader">[{oxmultilang ident="MO_OGONE_LOGLEVEL" }]</td>
          <td class="listheader">[{oxmultilang ident="MO_OGONE_MESSAGE" }]</td>
          <td class="listheader">[{oxmultilang ident="MO_OGONE_CONTEXT" }]</td>
          <td class="listheader">[{oxmultilang ident="MO_OGONE_EXTRA" }]</td>
        </tr>

        [{foreach from=$logfile item=oItem}]
        <tr class="ogone_logentry">
          [{ if $oItem.date }]
            <td class="listitem">[{$oItem.date|date_format:"%d.%m.%y&nbsp;%H:%M:%S"}]</td>
            <td class="listitem">[{$oItem.level}]</td>
            <td class="listitem">
              <div class="mo_ogone_logentry_shortened">[{$oItem.message|truncate:70:"...":true}]</div>
              <div class="mo_ogone_logentry_full" style="display:none">[{$oItem.message}]</div>
            </td>
            <td class="listitem">
              <div class="mo_ogone_logentry_shortened">[{$oItem.context|@debug_print_var|replace:"<br>":" "|truncate:100:"...":true}]</div>
              <div class="mo_ogone_logentry_full" style="display:none">[{$oItem.context|@debug_print_var}]</div>
            </td>
            <td class="listitem">
              <div class="mo_ogone_logentry_shortened">[{$oItem.extra|@debug_print_var|replace:"<br>":" "|truncate:100:"...":true}]</div>
              <div class="mo_ogone_logentry_full" style="display:none">[{$oItem.extra|@debug_print_var}]</div>
            </td>
          [{ else }]
            <td class="listitem">
              <div class="mo_ogone_logentry_shortened">[{$oItem.raw|truncate:100:"...":true}]</div>
              <div class="mo_ogone_logentry_full" style="display:none">[{$oItem.extra|@debug_print_var}]</div>
            </td>
          [{ /if }]
        </tr>
        [{/foreach}]
      </table>
    </form>
</div>
<script type="application/javascript">
    document.querySelectorAll('.ogone_logentry').forEach(function(obj) {
        obj.addEventListener('click', function (event) {
            event.currentTarget.querySelectorAll('.mo_ogone_logentry_shortened, .mo_ogone_logentry_full').forEach(function(element) {
                if (element.style.display === 'none') {
                    element.style.display = 'block';
                } else {
                    element.style.display = 'none';
                }
            });
        });
    });
</script>