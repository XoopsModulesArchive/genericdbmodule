<h3><{$modulename}></h3>

<table class="outer" cellpadding="1" cellspacing="1" border="0" width="100%">
    <tr>
        <th colspan="2" align="center"><{$_ITEM}><{$_DETAIL}></th>
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
        <td width="33%" align="right">
            <form action="update.php" method="post">
                <input type="hidden" name="iid" value="<{$iid}>">
                <input type="submit" value="<{$_EDIT}>">
            </form>
        </td>
        <td width="34%" align="center">
            <form action="delete.php" method="post">
                <input type="hidden" name="iid" value="<{$iid}>">
                <input type="submit" value="<{$_DELETE}>">
            </form>
        </td>
        <td width="33%" align="left">
            <form action="index.php" method="post">
                <input type="submit" value="<{$_RETURN_LIST}>">
            </form>
        </td>
    </tr>
</table>
