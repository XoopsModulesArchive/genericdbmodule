<h3><{$modulename}></h3>

<table class="outer" cellpadding="1" cellspacing="1" border="0" width="100%">
    <tr>
        <th colspan="2" align="center"><{$_ITEM}><{$_DELETE}></th>
    </tr>
    <{foreach from=$item_defs item="item" key="item_name"}>
    <tr>
        <td class="head" width="30%">
            <{$item.caption}>
            <{if $item.show_desc !== ''}><br><span style="font-weight: normal;"><{$item.show_desc}></span><{/if}>
        </td>
        <td class="<{cycle values="odd,even"}>"><{$item.value}></td>
    </tr>
    <{/foreach}>
</table>
<br>

<table width="100%">
    <tr>
        <td colspan="3" align="center">
            <font color="red"><{$_DELETE_CONFIRM_MSG}></font>
        </td>
    </tr>
    <tr>
        <td width="40%" align="right">
            <form action="delete.php" method="post">
                <input type="hidden" name="op" value="delete">
                <{$token}>
                <input type="hidden" name="iid" value="<{$iid}>">
                <input type="submit" value="<{$_DELETE}>">
            </form>
        </td>
        <td width="20%"></td>
        <td width="40%" align="left">
            <form action="detail.php" method="get">
                <input type="hidden" name="iid" value="<{$iid}>">
                <input type="submit" value="<{$_CANCEL}>">
            </form>
        </td>
    </tr>
</table>
