<{if count($errors) > 0}>
<div class="errorMsg">
    <{foreach item=error from=$errors}>
    <{$error}><br>
    <{/foreach}>
</div>
<br>
<{/if}>

<table width="100%">
    <tr>
        <td colspan="2" align="center">
            <span style="color: red; "><{$_DELETE_CONFIRM_MSG}></span>
        </td>
    </tr>
    <tr>
        <td width="50%" align="center">
            <form action="delete.php" method="post">
                <input type="hidden" name="did" value="<{$item_defs.did.value}>">
                <{$token}>
                <input type="hidden" name="op" value="delete">
                <input type="submit" value="<{$_DELETE}>">
            </form>
        </td>
        <td width="50%" align="center">
            <form action="detail.php" method="get">
                <input type="hidden" name="did" value="<{$item_defs.did.value}>">
                <input type="submit" value="<{$_CANCEL}>">
            </form>
        </td>
    </tr>
</table>
<br>

<table class="outer" cellpadding="1" cellspacing="1" border="0">
    <tr>
        <th colspan="2" align="center"><{$xoops_modulename}> <{$_DELETE}></th>
    </tr>
    <tr>
        <td class="head" nowrap="nowrap" width="20%"><{$cfg_id_caption}></td>
        <td class="<{cycle values="odd,even"}>"><{$item_defs.did.value}></td>
    </tr>
    <{foreach from=$item_defs item="item" key="item_name"}>
    <{if $item.detail}>
    <tr>
        <td class="head" nowrap="nowrap" width="20%">
            <{$item.caption}>
            <{if $item.show_desc !== ''}><br><span style="font-weight: normal; font-size: 80%;"><{$item.show_desc}></span><{/if}>
        </td>
        <td class="<{cycle values="odd,even"}>">
            <{if $item.type == 'image'}>
            <{if $item.value != ''}>
            <a href="download.php?did=<{$item_defs.did.value}>&amp;col_name=<{$item_name}>" rel="lightbox[01]"><img src="download.php?did=<{$item_defs.did.value}>&amp;col_name=<{$item_name}>" alt="<{$item.caption}>" width="<{$item.width}>px"></a>
            <{/if}>
            <{elseif $item.type == 'file'}>
            <{if $item.value != ''}>
            <a href="download.php?did=<{$item_defs.did.value}>&amp;col_name=<{$item_name}>" target="_blank"><{$item.value}></a>
            <{/if}>
            <{else}>
            <{$item.value}>
            <{/if}>
        </td>
    </tr>
    <{/if}>
    <{/foreach}>
</table>
