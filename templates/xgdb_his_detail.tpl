<div align="center">
    <{if $op == 'search'}>
    <form action="his_search.php" method="post">
        <input type="hidden" name="op" value="back_search">
        <input type="submit" value="<{$_BACK}>">
    </form>
    <{else}>
    <form action="detail.php" method="get">
        <input type="hidden" name="did" value="<{$did}>">
        <input type="submit" value="<{$_BACK}>">
    </form>
    <{/if}>
</div>
<br>

<{if $operation_raw == 'update'}>
<table class="outer" cellpadding="1" cellspacing="1" border="0">
    <tr>
        <th colspan="3" align="center"><{$xoops_modulename}> <{$_HIS_TITLE}> <{$_DETAIL}></th>
    </tr>
    <tr>
        <td class="head" nowrap="nowrap" width="20%"><{$cfg_id_caption}></td>
        <td class="<{cycle values="odd,even"}>" colspan="2">
          <a href="<{$xoops_url}>/modules/<{$xoops_dirname}>/detail.php?did=<{$item_defs.did.value}>" target="_blank"><{$item_defs.did.value}></a>
        </td>
    </tr>
    <tr>
        <td class="head" nowrap="nowrap" width="20%"><{$_OPERATION}></td>
        <td class="<{cycle values="odd,even"}>" colspan="2"><{$operation}></td>
    </tr>
    <tr>
        <td class="head" nowrap="nowrap" width="20%"><{$_UPDATE_UNAME}></td>
        <td class="<{cycle values="odd,even"}>" colspan="2">
          <{$item_defs.uname.value}>
        </td>
    </tr>
    <tr>
        <td class="head" nowrap="nowrap" width="20%"><{$_UPDATE_DATE}></td>
        <td class="<{cycle values="odd,even"}>" colspan="2">
          <{$item_defs.update_date.value}>
        </td>
    </tr>
    <tr>
        <td class="head" nowrap="nowrap" width="20%"></td>
        <td class="head" nowrap="nowrap" width="40%"><{$_BEFORE_TITLE}></td>
        <td class="head" nowrap="nowrap" width="40%"><{$_AFTER_TITLE}></td>
    </tr>
    <{foreach from=$item_defs item="item" key="item_name"}>
    <{if $item.detail}>
    <tr class="<{cycle values="odd,even"}>">
        <td class="head" nowrap="nowrap" width="20%">
            <{$item.caption}>
            <{if $item.show_desc !== ''}><br><span style="font-weight: normal; font-size: 80%;"><{$item.show_desc}></span><{/if}>
        </td>
        <td>
          <{if $bef_item_defs.$item_name.value != $item.value}><span style="color: red; "><{/if}>
          <{$bef_item_defs.$item_name.value}>
          <{if $bef_item_defs.$item_name.value != $item.value}></span><{/if}>
        </td>
        <td>
          <{if $bef_item_defs.$item_name.value != $item.value}><span style="color: red; "><{/if}>
          <{$item.value}>
          <{if $bef_item_defs.$item_name.value != $item.value}></span><{/if}>
        </td>
    </tr>
    <{/if}>
    <{/foreach}>
</table>
<{else}>
<table class="outer" cellpadding="1" cellspacing="1" border="0">
    <tr>
        <th colspan="2" align="center"><{$xoops_modulename}> <{$_HIS_TITLE}> <{$_DETAIL}></th>
    </tr>
    <tr>
        <td class="head" nowrap="nowrap" width="20%"><{$cfg_id_caption}></td>
        <td class="<{cycle values="odd,even"}>">
          <a href="<{$xoops_url}>/modules/<{$xoops_dirname}>/detail.php?did=<{$item_defs.did.value}>" target="_blank"><{$item_defs.did.value}></a>
        </td>
    </tr>
    <tr>
        <td class="head" nowrap="nowrap" width="20%"><{$_OPERATION}></td>
        <td class="<{cycle values="odd,even"}>"><{$operation}></td>
    </tr>
    <tr>
        <td class="head" nowrap="nowrap" width="20%"><{$_UPDATE_UNAME}></td>
        <td class="<{cycle values="odd,even"}>">
          <{$item_defs.uname.value}>
        </td>
    </tr>
    <tr>
        <td class="head" nowrap="nowrap" width="20%"><{$_UPDATE_DATE}></td>
        <td class="<{cycle values="odd,even"}>">
          <{$item_defs.update_date.value}>
        </td>
    </tr>
    <{foreach from=$item_defs item="item" key="item_name"}>
    <{if $item.detail}>
    <tr>
        <td class="head" nowrap="nowrap" width="20%">
            <{$item.caption}>
            <{if $item.show_desc !== ''}><br><span style="font-weight: normal; font-size: 80%;"><{$item.show_desc}></span><{/if}>
        </td>
        <td class="<{cycle values="odd,even"}>">
            <{$item.value}>
        </td>
    </tr>
    <{/if}>
    <{/foreach}>
</table>
<{/if}>
