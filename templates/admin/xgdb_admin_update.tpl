<h3><{$modulename}></h3>

<{if count($errors) > 0}>
<div class="errorMsg">
    <{foreach item=error from=$errors}>
    <{$error}><br>
    <{/foreach}>
</div>
<br>
<{/if}>
<form name="form" action="" method="post">
    <table class="outer" cellpadding="1" cellspacing="1" border="0" width="100%">
        <tr>
            <th colspan="2" align="center"><{$_ITEM}><{$_UPDATE}></th>
        </tr>
        <{foreach from=$item_defs item="item" key="item_name"}>
        <tr>
            <td class="head" width="30%">
                <{$item.caption}>
                <{if $item.required}><{$_REQ_MARK}><{/if}>
                <{if $item.input_desc !== ''}><br><span style="font-weight: normal;"><{$item.input_desc}></span><{/if}>
            </td>
            <td class="<{cycle values="odd,even"}>">
                <{$item.value}>
                <font color="red"><{$item.error}></font>
            </td>
        </tr>
        <{/foreach}>
    </table>
    <br>
    <div align="center">
        <input type="hidden" name="iid" value="<{$iid}>">
        <input type="hidden" name="op" value="update">
        <{$token}>
        <input type="submit" value="<{$_UPDATE}>">
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input type="submit" name="cancel" value="<{$_CANCEL}>">
    </div>
</form>
