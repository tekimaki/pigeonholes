{strip}
{if $gBitSystem->isFeatureActive( 'pigeonholes_display_path' ) && $pigeonData}
	<div class="structurebar pigeonholesbar">
		{foreach from=$pigeonData item=pigeonItem}
			<span class="path">{$pigeonItem.display_path}</span>
		{/foreach}
	</div><!-- end .structurebar -->
{/if}
{/strip}
