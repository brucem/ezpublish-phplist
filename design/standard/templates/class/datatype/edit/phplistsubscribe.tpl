{def $lists=fetch(phplist,fetchLists,hash())}
<div class="block">
    <label>{'Subscribe to List'|i18n( 'design/standard/class/datatype' )}</label>
    {if eq(count($lists),0)}
        <p>{'There are no active PHPList Lists'|i18n( 'design/standard/class/datatype' )}</p>
        <input type="hidden" name="ContentClass_phplistsubscribe_list_{$class_attribute.id}" value="-1" />
    {else}
        <select name="ContentClass_phplistsubscribe_list_{$class_attribute.id}">
            <option value="-1">{'Choose List'|i18n( 'design/standard/class/datatype' )}...</option>
            {foreach $lists as $list}
                <option value="{$list.id}" {if eq($class_attribute.data_int1,$list.id)} selected="selected"{/if}>{$list.name|wash(xhtml)}</option>
            {/foreach}
        </select>
    {/if}
</div>
<div class="block">
    <label>{'Default value'|i18n( 'design/standard/class/datatype' )}:</label>
    <input type="checkbox" name="ContentClass_phplistsubscribe_list_default_value_{$class_attribute.id}" {$class_attribute.data_int3|choose( '', 'checked="checked"' )} />
    <input type="hidden" name="ContentClass_phplistsubscribe_list_default_value_{$class_attribute.id}_exists" value="1" />
</div>
{undef $lists}

