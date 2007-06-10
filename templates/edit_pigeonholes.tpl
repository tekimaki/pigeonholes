<div class="floaticon">{bithelp}</div>

<div class="edit pigeonholes">
	<div class="header">
		<h1>{tr}Edit Categories{/tr}</h1>
	</div>

	<div class="body">
		{formfeedback hash=$feedback}
		{form legend="Create / Edit Category"}
			{if $gContent->mStructureId}
				<input type="hidden" name="structure_id" value="{$gContent->mStructureId}" />
				<input type="hidden" name="content_id" value="{$pigeonInfo.content_id}" />
				<input type="hidden" name="action" value="{$smarty.request.action}" />

				<div class="row">
					{formlabel label="Parent" for="pigeonhole-parent"}
					{forminput}
						{* we need to disable dropdown when editing since it might confus users when nothing happens *}
						{if $pigeonInfo.content_id}
							{html_options id="pigeonhole-parent" name="pigeonhole[parent_id]" values=$pigeonStructure options=$pigeonStructure selected=$pigeonInfo.parent_id disabled=disabled}
						{else}
							{html_options id="pigeonhole-parent" name="pigeonhole[parent_id]" values=$pigeonStructure options=$pigeonStructure selected=$pigeonInfo.parent_id}
						{/if}
						{formhelp note="Pick where you would like to create a new sub-category. To change the hierarchy of the categories, please visit the change structure page."}
					{/forminput}
				</div>
			{/if}

			<div class="row">
				{formlabel label="Title" for="pigeonhole-title"}
				{forminput}
					<input type="text" size="50" id="pigeonhole-title" name="pigeonhole[title]" value="{$pigeonInfo.title|escape}" />
				{/forminput}
			</div>

			{if $gBitSystem->isFeatureActive('pigeonholes_display_description')}
				<div class="row">
					{textarea id="pigeonhole-desc" name="pigeonhole[edit]" note="A description of the category. This will be visible when users view this particular category." label="Description"}{$pigeonInfo.data}{/textarea}
				</div>
			{/if}

			{if $gBitSystem->isFeatureActive( 'pigeonholes_allow_forbid_insertion') }
				<div class="row">
					{formlabel label="Forbid Insertion" for="pigeonhole-no-insert"}
					{forminput}
						<input type="checkbox" name="pigeonhole[prefs][no_insert]" id="pigeonhole-no-insert" {if $gContent->mPrefs.no_insert}checked{/if} />
						{formhelp note="Forbids inserting new content into this category. Useful for categories which are meant to hold other categories in a heirarchy where only leaf categories should hold content."}
					{/forminput}
				</div>
			{/if}

			{if $gBitSystem->isFeatureActive( 'pigeonholes_themes' )}
				<div class="row">
					{formlabel label="Theme" for="pigeonhole-style"}
					{forminput}
						{html_options id="pigeonhole-style" name="pigeonhole[prefs][style]" output=$styles values=$styles selected=$gContent->mPrefs.style}
						{formhelp note="This theme will be applied when viewing any page belonging to this category." warning="Setting is inherited from parent category."}
					{/forminput}
				</div>
			{/if}

			{if $gBitSystem->isFeatureActive( 'pigeonholes_permissions' )}
				<div class="row">
					{formlabel label="Permission" for="perm"}
					{forminput}
						{html_options name="pigeonhole[prefs][permission]" id="perm" options=$perms selected=`$gContent->mPrefs.permission`}
						{formhelp note="Permission required to view any page in this category." warning="Setting is inherited from parent category."}
					{/forminput}
				</div>
			{/if}

			{if $gBitSystem->isFeatureActive( 'pigeonholes_groups' )}
				<div class="row">
					{formlabel label="Group" for="group"}
					{forminput}
						{html_options name="pigeonhole[prefs][group]" id="group" options=$groups selected=`$gContent->mPrefs.group_id`}
						{formhelp note="Users of only this group can view the content of this category." warning="Setting is inherited from parent category."}
					{/forminput}
				</div>
			{/if}

			{if !$gContent->mStructureId}
				<div class="row">
					{formlabel label="Content" for="pigeonhole-content"}
					{forminput}
						{html_options options=$contentTypes name=content_type_guid selected=$contentSelect}
					{/forminput}

					{forminput}
						{html_options multiple="multiple" size="12" name="pigeonhole[members][]" id="pigeonhole-content" values=$contentList options=$contentList selected=$pigeonInfo.selected_members}
					{/forminput}

					{forminput}
						<input type="text" size="30" name="find_objects" value="{$smarty.request.find_objects}" /> 
						<input type="submit" value="{tr}Apply filter{/tr}" name="search_objects" />
					{/forminput}
				</div>
			{/if}

			<div class="row submit">
				<input type="submit" name="pigeonhole_store" value="{tr}Save Category{/tr}" />
			</div>
		{/form}

		{include file="bitpackage:pigeonholes/view_structure_inc.tpl"}
	</div><!-- end .body -->
</div><!-- end .edit -->
