<table class="outer" cellpadding="1" cellspacing="1" border="0">
    <tr>
        <td class="head" align="center" nowrap="nowrap">ID</td>
        <{foreach from=$block.item_defs item="item" key="item_name"}>
        <{if $item.list && $item.type != 'image' && $item.type != 'file'}>
        <td class="head" align="center" nowrap="nowrap"><{$item.caption}></td>
        <{/if}>
        <{/foreach}>
    </tr>
    <{foreach item="info" from=$block.infos}>
    <tr class="<{cycle values="odd,even"}>">
        <td nowrap align="center"><a href="<{$xoops_url}>/modules/<{$block.dirname}>/detail.php?did=<{$info.did}>"><{$info.did}></a></td>
        <{foreach from=$block.item_defs item="item" key="item_name"}>
        <{if $item.list && $item.type != 'image' && $item.type != 'file'}>
        <td><{$info.$item_name}></td>
        <{/if}>
        <{/foreach}>
    </tr>
    <{/foreach}>
</table>
