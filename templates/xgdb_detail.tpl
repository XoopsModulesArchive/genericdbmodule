<{if $perm}>
<table width="100%">
    <tr>
        <td align="center">
            <form action="update.php" method="post">
                <input type="hidden" name="did" value="<{$item_defs.did.value}>">
                <input type="submit" value="<{$_UPDATE}>">
            </form>
        </td>
        <td align="center">
            <form action="delete.php" method="post">
                <input type="hidden" name="did" value="<{$item_defs.did.value}>">
                <input type="submit" value="<{$_DELETE}>">
            </form>
        </td>
        <td align="center">
            <form action="index.php" method="post">
                <input type="hidden" name="op" value="back_search">
                <input type="submit" value="<{$_BACK}>">
            </form>
        </td>
    </tr>
</table>
<br>
<{else}>
<table width="100%">
    <tr>
        <td align="center">
            <form action="index.php" method="post">
                <input type="hidden" name="op" value="back_search">
                <input type="submit" value="<{$_BACK}>">
            </form>
        </td>
    </tr>
</table>
<br>
<{/if}>

<table class="outer" cellpadding="1" cellspacing="1" border="0">
    <tr>
        <th colspan="2" align="center"><{$xoops_modulename}> <{$_DETAIL}></th>
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
            <a href="download.php?did=<{$item_defs.did.value}>&amp;col_name=<{$item_name}>"><{$item.value}></a>
            <{/if}>
            <{else}>
            <{$item.value}>
            <{/if}>
        </td>
    </tr>
    <{/if}>
    <{/foreach}>
</table>
<br>

<{if $his_perm}>
<table class="outer" cellpadding="1" cellspacing="1" border="0" id="xgdb_his_show_table">
    <tr>
        <th colspan="4" align="center">
            <{$_HIS_TITLE}>
            <a href="" id="xgdb_his_show"><{$_SHOW}></a>
            <a href="" id="xgdb_his_hide" style="display: none;"><{$_HIDE}></a>
        </th>
    </tr>
    <tr>
        <td class="head"></td>
        <td class="head" nowrap="nowrap" align="center"><{$_OPERATION}></td>
        <td class="head" nowrap="nowrap" align="center"><{$_UPDATE_UNAME}></td>
        <td class="head" nowrap="nowrap" align="center"><{$_UPDATE_DATE}></td>
    </tr>
    <{foreach from=$histories item="history"}>
    <tr class="<{cycle values="odd,even"}>" style="display: none;">
        <td align="center"><a href="<{$xoops_url}>/modules/<{$xoops_dirname}>/his_detail.php?hid=<{$history.hid}>"><{$_DETAIL}></a></td>
        <td align="center"><{$history.operation}></td>
        <td><{$history.update_uname}></td>
        <td align="center"><{$history.update_date}></td>
    </tr>
    <{/foreach}>
</table>
<br>
<{/if}>

<{include file='db:system_notification_select.html'}>

<br>
<{$commentsnav}>
<div style="text-align: center;"><{$lang_notice}></div>
<{if $comment_mode == "flat"}>
    <{include file="db:system_comments_flat.html"}>
<{elseif $comment_mode == "thread"}>
    <{include file="db:system_comments_thread.html"}>
<{elseif $comment_mode == "nest"}>
    <{include file="db:system_comments_nest.html"}>
<{/if}>
