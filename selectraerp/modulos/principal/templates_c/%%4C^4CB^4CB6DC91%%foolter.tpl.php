<?php /* Smarty version 2.6.21, created on 2017-10-04 17:51:15
         compiled from foolter.tpl */ ?>
<?php if ($_GET['msg'] != ""): ?>
    <?php echo '
        <script type="text/javascript">//<![CDATA[
            Ext.onReady(function() {
                Ext.Msg.alert("Mensaje","'; ?>
<?php echo $_GET['msg']; ?>
<?php echo '");
                //alert(\'{*/literal}{$smarty.get.msg}{literal*}\');
            });
        //]]>
        </script>
    '; ?>

<?php endif; ?>