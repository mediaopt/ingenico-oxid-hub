[{assign var=template_title value=mo_ogone__payment_form}]

[{oxstyle include="css/mo_ogone.css"}]

[{capture append="oxidBlock_content"}]

  [{* ordering steps *}]
  [{include file="page/checkout/inc/steps.tpl" active=4}]

  <form id="mo_ogone__form" action="[{$mo_ogone__form_action}]" method="post">
    <p>
      Sie werden nun automatisch zur <strong>Bezahlseite weitergeleitet</strong>. <br />
      Falls Sie nicht automatisch weitergeitet werden<br />
      <button class="submitButton largeButton" type="submit">klicken Sie hier</button>
    </p>

    [{if $mo_ogone__debug}]
      <div style="border: 2px solid red; font-size: 70%; padding: 10px; height: 300px; overflow: auto;">
        <strong style="display: block; background: black; color: white; padding: 5px; margin-bottom: 5px;">Debug-Ausgabe:</strong>
      [{/if}]

      [{foreach from=$mo_ogone__hidden_fields key=name item=value}]
        [{if $mo_ogone__debug}]
          <label style="display: block; font-weight: bold;">[{$name}]:
            <input type="text" name="[{$name}]" value="[{$value}]" style="width: 100%; font-size: 80%; -moz-box-sizing: border-box;" />
          </label>
        [{else}]
          <input type="hidden" name="[{$name}]" value="[{$value}]" />
        [{/if}]
      [{/foreach}]

      [{* Submit form automatically via JS *}]
      [{if !$mo_ogone__debug}]
        <script>
          window.onload = function () {
            setTimeout(function () {
              document.getElementById('mo_ogone__form').submit();
            }, 5000);
          };
        </script>
      [{/if}]

      [{if $mo_ogone__debug}]
      </div>
    [{/if}]
  </form>

  [{insert name="oxid_tracker" title=$template_title}]
[{/capture}]

[{include file="layout/page.tpl"}]