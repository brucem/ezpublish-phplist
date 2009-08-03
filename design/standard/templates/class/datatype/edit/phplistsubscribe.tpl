{let lists=fetch(phplist,fetchLists,hash())}
<div class="block">
<label>Subscribe to List</label>
{section show=eq(count($lists),0)}
<p>There are no active PHPList Lists</p>
<input type="hidden" name="ContentClass_phplistsubscribe_list_{$class_attribute.id}" value="-1" />
{section-else}
<select name="ContentClass_phplistsubscribe_list_{$class_attribute.id}">
  <option value="-1">Choose List...</option>
  {section loop=$lists}
  <option value="{$item.id}" {section show=eq($class_attribute.data_int1,$item.id)} selected{/section}>{$item.name|wash(xhtml)}</option>
  {/section}
</select>
{/section}
</div>
<div class="block">
  <label>{'Default value'|i18n( 'design/standard/class/datatype' )}:</label>
  <input type="checkbox" name="ContentClass_phplistsubscribe_list_default_value_{$class_attribute.id}" {$class_attribute.data_int3|choose( '', 'checked="checked"' )} />
  <input type="hidden" name="ContentClass_phplistsubscribe_list_default_value_{$class_attribute.id}_exists" value="1" />
</div>
{/let}

