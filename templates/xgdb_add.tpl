<{if count($errors) > 0}>
<div class="errorMsg">
    <{foreach item=error from=$errors}>
    <{$error}><br>
    <{/foreach}>
</div>
<br>
<{/if}>

<form name="form" action="add.php" method="post" enctype="multipart/form-data" >
    <table class="outer" cellpadding="1" cellspacing="1" border="0">
        <tr>
            <th colspan="2" align="center"><{$xoops_modulename}> <{$_ADD}></th>
        </tr>
        <{foreach from=$item_defs item="item" key="item_name"}>
        <{if $item.add}>
        <tr>
            <td class="head" nowrap="nowrap" width="20%">
                <{$item.caption}>
                <{if $item.required}><{$_REQ_MARK}><{/if}>
                <{if $item.input_desc !== ''}><br><span style="font-weight: normal; font-size: 80%;"><{$item.input_desc}></span><{/if}>
            </td>
            <td class="<{cycle values="odd,even"}>">
                <{$item.value}>
                <span style="color: red; "><{$item.error}></span>
            </td>
        </tr>
        <{/if}>
        <{/foreach}>
    </table>
    <br>
    <div align="center">
        <input type="hidden" name="op" value="add">
        <{$token}>
        <input type="submit" value="<{$_ADD}>">
    </div>
</form>
