<{if count($errors) > 0}>
<div class="errorMsg">
    <{foreach item=error from=$errors}>
    <{$error}><br>
    <{/foreach}>
</div>
<br>
<{/if}>

<form name="form" action="his_search.php" method="post">
    <table class="outer" cellpadding="1" cellspacing="1" border="0">
        <tr>
            <th colspan="2" align="center"><{$xoops_modulename}> <{$_HIS_TITLE}> <{$_SEARCH}></th>
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
        <th colspan="4" align="center"><{$_SEARCH_RESULT}></th>
    </tr>
    <tr>
        <td class="head" align="center" nowrap="nowrap">
            <{if $order_item != 'hid' || $order != 'asc'}><a href="<{$xoops_url}>/modules/<{$xoops_dirname}>/his_search.php?start=<{$start}>&amp;<{$queries}>&amp;order_item=hid&amp;order=asc"><img src="<{$xoops_url}>/modules/<{$xoops_dirname}>/images/asc.gif"></a><{/if}>
            <{$_HIS_ID}>
            <{if $order_item != 'hid' || $order != 'desc'}><a href="<{$xoops_url}>/modules/<{$xoops_dirname}>/his_search.php?start=<{$start}>&amp;<{$queries}>&amp;order_item=hid&amp;order=desc"><img src="<{$xoops_url}>/modules/<{$xoops_dirname}>/images/desc.gif"></a><{/if}>
        </td>
        <td class="head" align="center" nowrap="nowrap">
            <{if $order_item != 'operation' || $order != 'asc'}><a href="<{$xoops_url}>/modules/<{$xoops_dirname}>/his_search.php?start=<{$start}>&amp;<{$queries}>&amp;order_item=operation&amp;order=asc"><img src="<{$xoops_url}>/modules/<{$xoops_dirname}>/images/asc.gif"></a><{/if}>
            <{$_OPERATION}>
            <{if $order_item != 'operation' || $order != 'desc'}><a href="<{$xoops_url}>/modules/<{$xoops_dirname}>/his_search.php?start=<{$start}>&amp;<{$queries}>&amp;order_item=operation&amp;order=desc"><img src="<{$xoops_url}>/modules/<{$xoops_dirname}>/images/desc.gif"></a><{/if}>
        </td>
        <td class="head" align="center" nowrap="nowrap">
            <{if $order_item != 'update_date' || $order != 'asc'}><a href="<{$xoops_url}>/modules/<{$xoops_dirname}>/his_search.php?start=<{$start}>&amp;<{$queries}>&amp;order_item=update_date&amp;order=asc"><img src="<{$xoops_url}>/modules/<{$xoops_dirname}>/images/asc.gif"></a><{/if}>
            <{$_UPDATE_DATE}>
            <{if $order_item != 'update_date' || $order != 'desc'}><a href="<{$xoops_url}>/modules/<{$xoops_dirname}>/his_search.php?start=<{$start}>&amp;<{$queries}>&amp;order_item=update_date&amp;order=desc"><img src="<{$xoops_url}>/modules/<{$xoops_dirname}>/images/desc.gif"></a><{/if}>
        </td>
        <td class="head" align="center" nowrap="nowrap">
            <{if $order_item != 'did' || $order != 'asc'}><a href="<{$xoops_url}>/modules/<{$xoops_dirname}>/his_search.php?start=<{$start}>&amp;<{$queries}>&amp;order_item=did&amp;order=asc"><img src="<{$xoops_url}>/modules/<{$xoops_dirname}>/images/asc.gif"></a><{/if}>
            <{$cfg_id_caption}>
            <{if $order_item != 'did' || $order != 'desc'}><a href="<{$xoops_url}>/modules/<{$xoops_dirname}>/his_search.php?start=<{$start}>&amp;<{$queries}>&amp;order_item=did&amp;order=desc"><img src="<{$xoops_url}>/modules/<{$xoops_dirname}>/images/desc.gif"></a><{/if}>
        </td>
    </tr>
    <{foreach item="info" from=$infos}>
    <tr class="<{cycle values="odd,even"}>">
        <td align="center"><a href="his_detail.php?hid=<{$info.hid}>&amp;op=search"><{$info.hid}></a></td>
        <td align="center"><{$info.operation}></td>
        <td align="center"><{$info.update_date}></td>
        <td nowrap align="center"><a href="detail.php?did=<{$info.did}>" target="_blank"><{$info.did}></a></td>
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
