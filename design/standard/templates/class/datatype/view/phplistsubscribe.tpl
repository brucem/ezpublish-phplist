{let list=fetch(phplist,fetchList,hash(listID,$class_attribute.data_int1))}
	<div class="block">
		<div class="element">
			<label>PHPList List</label>
			<p>{$list.name|wash(xhtml)}</p>
		</div>
	</div>
  <div class="block">
    <label>{'Default value'|i18n( 'design/standard/class/datatype' )}:</label>
    <p>{$class_attribute.data_int3|choose( 'Unchecked'|i18n( 'design/standard/class/datatype' ), 'Checked'|i18n( 'design/standard/class/datatype' ) )}</p>
  </div>
{/let}
