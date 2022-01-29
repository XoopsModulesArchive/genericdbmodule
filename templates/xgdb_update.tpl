<{if count($errors) > 0}>
<div class="errorMsg">
    <{foreach item=error from=$errors}>
    <{$error}><br>
    <{/foreach}>
</div>
<br>
<{/if}>

<form name="form" action="update.php" method="post" enctype="multipart/form-data" >
    <table class="outer" cellpadding="1" cellspacing="1" border="0">
        <tr>
            <th colspan="2" align="center"><{$xoops_modulename}> <{$_UPDATE}></th>
        </tr>
        <tr>
            <td class="head" nowrap="nowrap" width="20%"><{$cfg_id_caption}></td>
            <td class="<{cycle values="odd,even"}>"><{$item_defs.did.value}></td>
        </tr>
        <{foreach from=$update_item_defs item="item" key="item_name"}>
        <tr>
            <td class="head" nowrap="nowrap">
                <{$item.caption}>
                <{if $item.required}><{$_REQ_MARK}><{/if}>
                <{if $item.input_desc !== ''}><br><span style="font-weight: normal; font-size: 80%;"><{$item.input_desc}></span><{/if}>
            </td>
            <td class="<{cycle values="odd,even"}>">
                <{if $item.type == 'image' && $item.current_value}>
                <a href="download.php?did=<{$item_defs.did.value}>&amp;col_name=<{$item_name}>" rel="lightbox[01]"><img src="download.php?did=<{$item_defs.did.value}>&amp;col_name=<{$item_name}>" alt="<{$item.caption}>" width="<{$item.width}>px"></a>&nbsp;&nbsp;&nbsp;
                <{elseif $item.type == 'file' && $item.current_value}>
                <a href="download.php?did=<{$item_defs.did.value}>&amp;col_name=<{$item_name}>" target="_blank"><{$item.current_value}></a>&nbsp;&nbsp;&nbsp;
                <{/if}>
                <{if ($item.type == 'file' || $item.type == 'image') && $item.current_value != ''}><br><{/if}>
                <{$item.value}>
                <{if ($item.type == 'file' || $item.type == 'image') && $item.current_value && !$item.required}>
                <label style="margin-left: 1em;"><input type="checkbox" name="delete_file_names[<{$item_name}>]" value="1"><{$_DELETE}></label>
                <{/if}>
                <font color="red"><{$item.error}></font>
            </td>
        </tr>
        <{/foreach}>
    </table>
    <br>

    <table width="100%">
        <tr>
            <td width="50%" align="center">
                <input type="submit" name="update" value="<{$_UPDATE}>">
            </td>
            <td width="50%" align="center">
                <input type="submit" name="cancel" value="<{$_CANCEL}>">
            </td>
        </tr>
    </table>
    <input type="hidden" name="did" value="<{$item_defs.did.value}>">
    <input type="hidden" name="op" value="update">
    <{$token}>
</form>
