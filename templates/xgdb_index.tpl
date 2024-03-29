<{if count($errors) > 0}>
<div class="errorMsg">
    <{foreach item=error from=$errors}>
    <{$error}><br>
    <{/foreach}>
</div>
<br>
<{/if}>

<form name="form" action="index.php" method="post">
    <table class="outer" cellpadding="1" cellspacing="1" border="0">
        <tr>
            <th colspan="2" align="center"><{$xoops_modulename}> <{$_SEARCH}></th>
        </tr>
        <{foreach from=$search_defs item="item" key="item_name"}>
        <{if !isset($item.is_range_item)}>
        <tr>
            <td class="head" nowrap="nowrap">
                <{$item.caption}>
                <{if $item.search_desc !== ''}><br><span style="font-weight: normal; font-size: 80%;"><{$item.search_desc}></span><{/if}>
            </td>
            <td class="<{cycle values="odd,even"}>">
                <{$item.value}>
                <{if $item.disp_cond}><{$item.condition}><{/if}>
                <span style="color: red; "><{$item.error}></span>
            </td>
        </tr>
        <{/if}>
        <{/foreach}>
    </table>
    <br>
    <div align="center">
        <input type="hidden" name="op" value="search">
        <input type="submit" name="button" value="<{$_SEARCH}>">
    </div>
</form>

<{if isset($infos)}>
<br>
<table class="outer" cellpadding="1" cellspacing="1" border="0">
    <tr>
        <th colspan="<{$list_item_num}>" align="center"><{$_SEARCH_RESULT}></th>
    </tr>
    <tr>
        <td class="head" align="center" nowrap="nowrap">
            <{if $order_item != 'did' || $order != 'asc'}><a href="<{$xoops_url}>/modules/<{$xoops_dirname}>/index.php?start=<{$start}>&amp;<{$queries}>&amp;order_item=did&amp;order=asc"><img src="<{$xoops_url}>/modules/<{$xoops_dirname}>/images/asc.gif"></a><{/if}>
            <{$cfg_id_caption}>
            <{if $order_item != 'did' || $order != 'desc'}><a href="<{$xoops_url}>/modules/<{$xoops_dirname}>/index.php?start=<{$start}>&amp;<{$queries}>&amp;order_item=did&amp;order=desc"><img src="<{$xoops_url}>/modules/<{$xoops_dirname}>/images/desc.gif"></a><{/if}>
        </td>
        <{foreach from=$item_defs item="item" key="item_name"}>
        <{if $item.list}>
        <td class="head" align="center" nowrap="nowrap">
            <{if !($order_item == $item_name && $order == 'asc')}><a href="<{$xoops_url}>/modules/<{$xoops_dirname}>/index.php?start=<{$start}>&amp;<{$queries}>&amp;order_item=<{$item_name}>&amp;order=asc"><img src="<{$xoops_url}>/modules/<{$xoops_dirname}>/images/asc.gif"></a><{/if}>
            <{$item.caption}>
            <{if !($order_item == $item_name && $order == 'desc')}><a href="<{$xoops_url}>/modules/<{$xoops_dirname}>/index.php?start=<{$start}>&amp;<{$queries}>&amp;order_item=<{$item_name}>&amp;order=desc"><img src="<{$xoops_url}>/modules/<{$xoops_dirname}>/images/desc.gif"></a><{/if}>
        </td>
        <{/if}>
        <{/foreach}>
    </tr>
    <{foreach item="info" from=$infos}>
    <tr class="<{cycle values="odd,even"}>">
        <td nowrap align="center"><a href="detail.php?did=<{$info.did}>"><{$info.did}></a></td>
        <{foreach from=$item_defs item="item" key="item_name"}>
        <{if $item.list}>
        <td>
            <{if $item.type == 'image'}>
            <{if $info.$item_name != ''}>
            <a href="download.php?did=<{$info.did}>&amp;col_name=<{$item_name}>" rel="lightbox[01]"><img src="download.php?did=<{$info.did}>&amp;col_name=<{$item_name}>" alt="<{$info.caption}>" width="<{$item.width}>px"></a>
            <{/if}>
            <{elseif $item.type == 'file'}>
            <{if $info.$item_name != ''}>
            <a href="download.php?did=<{$info.did}>&amp;col_name=<{$item_name}>" target="_blank"><{$info.$item_name}></a>
            <{/if}>
            <{else}>
            <{$info.$item_name}>
            <{/if}>
        </td>
        <{/if}>
        <{/foreach}>
    </tr>
    <{/foreach}>
</table>

<table width="100%">
    <tr>
        <{if isset($pagenavi_html)}>
        <td width="20%" align="left"><{$pagenavi_html}></td>
        <{/if}>
        <td align="center" nowrap><{$pagenavi_info}></td>
        <{if isset($pagenavi_html)}>
        <td width="20%" align="right"><{$pagenavi_html}></td>
        <{/if}>
    </tr>
</table>
<{elseif $op == 'search' && !isset($infos)}>
<br>
<div style="text-align: center;"><{$_NOT_FOUND_MSG}></div>
<{/if}>
<br>

<{include file='db:system_notification_select.html'}>
