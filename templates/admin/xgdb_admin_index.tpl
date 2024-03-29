<h3><{$modulename}></h3>

<{if count($errors) > 0}>
<div class="errorMsg">
    <{foreach item=error from=$errors}>
    <{$error}><br>
    <{/foreach}>
</div>
<br>
<{/if}>

<center>
    <form action="add.php" method="post">
        <{$item_add_msg}>
        <input type="submit" value="<{$_ITEM_ADD}>">
    </form>
</center>
<br>

<table class="outer" cellpadding="1" cellspacing="1" border="0" width="100%">
    <tr>
        <th colspan="13" align="center"><{$_ITEM}><{$_LIST}></th>
    </tr>
    <tr>
        <td class="head" align="center" nowrap="nowrap"><{$_NAME}></td>
        <td class="head" align="center" nowrap="nowrap"><{$_CAPTION}></td>
        <td class="head" align="center" nowrap="nowrap"><{$_TYPE}></td>
        <td class="head" align="center" nowrap="nowrap"><{$_REQUIRED}></td>
        <td class="head" align="center" nowrap="nowrap"><{$_SEQUENCE}></td>
        <td class="head" align="center" nowrap="nowrap"><{$_SEARCH}></td>
        <td class="head" align="center" nowrap="nowrap"><{$_LIST}></td>
        <td class="head" align="center" nowrap="nowrap"><{$_ADD}></td>
        <td class="head" align="center" nowrap="nowrap"><{$_UPDATE}></td>
        <td class="head" align="center" nowrap="nowrap"><{$_DETAIL}></td>
        <td class="head" align="center" nowrap="nowrap"><{$_DUPLICATE}></td>
        <td class="head" align="center" nowrap="nowrap"><{$_OPERATION}></td>
    </tr>
    <{foreach item="item" from=$items}>
    <tr class="<{cycle values="odd,even"}>">
        <td><{$item.name}></td>
        <td><{$item.caption}></td>
        <td><{$item.type_title}></td>
        <td align="center"><{if $item.required}><{$_YES_MARK}><{/if}></td>
        <td align="center"><{$item.sequence}></td>
        <td align="center"><{if $item.search}><{$_YES_MARK}><{/if}></td>
        <td align="center"><{if $item.list}><{$_YES_MARK}><{/if}></td>
        <td align="center"><{if $item.add}><{$_YES_MARK}><{/if}></td>
        <td align="center"><{if $item.update}><{$_YES_MARK}><{/if}></td>
        <td align="center"><{if $item.detail}><{$_YES_MARK}><{/if}></td>
        <td align="center"><{if $item.duplicate}><{$_YES_MARK}><{/if}></td>
        <td align="center">
            <a href="detail.php?iid=<{$item.iid}>"><{$_DETAIL}></a> &nbsp;
            <a href="update.php?iid=<{$item.iid}>"><{$_EDIT}></a> &nbsp;
            <a href="delete.php?iid=<{$item.iid}>"><{$_DELETE}></a> &nbsp;
        </td>
    </tr>
    <{/foreach}>
</table>
