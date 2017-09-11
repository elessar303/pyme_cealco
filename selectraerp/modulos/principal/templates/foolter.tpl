{if $smarty.get.msg neq ""}
    {literal}
        <script type="text/javascript">//<![CDATA[
            Ext.onReady(function() {
                Ext.Msg.alert("Mensaje","{/literal}{$smarty.get.msg}{literal}");
                //alert('{*/literal}{$smarty.get.msg}{literal*}');
            });
        //]]>
        </script>
    {/literal}
{/if}