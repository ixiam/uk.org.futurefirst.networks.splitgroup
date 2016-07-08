<div class="crm-block crm-form-block">
  <div class="crm-submit-buttons">
    {include file="CRM/common/formButtons.tpl" location="top"}
  </div>

  {foreach from=$elementNames item=elementName}
    <div class="crm-section">
      <div class="label">{$form.$elementName.label}</div>
      <div class="content">{$form.$elementName.html}</div>
      <div class="clear"></div>
    </div>
  {/foreach}

  <div class="crm-submit-buttons">
    {include file="CRM/common/formButtons.tpl" location="bottom"}
  </div>
</div><!-- /.crm-form-block -->

{if isset($subgroups)}
  <div class="crm-content-block">
    <div class="crm-results-block">
      <h3>{ts}Sub-groups created{/ts}</h3>
      <table id="subgroups-table">
        <thead>
          <tr>
            <th>{ts}Name{/ts}</th>
            <th>{ts}ID{/ts}</th>
            <th>{ts}Description{/ts}</th>
            <th>{ts}No. Contacts{/ts}</th>
            <th><!-- Actions --></th>
          </tr>
        </thead>
        <tbody>
          {foreach from=$subgroups item=subgroup}
            <tr>
              <td>{$subgroup.title}</td>
              <td>{$subgroup.id}</td>
              <td>{$subgroup.description}</td>
              <td>{$subgroup.contacts_added}</td>
              <td>
                <a href="{crmURL p='civicrm/group/search' q='reset=1&force=1&context=smog&gid='}{$subgroup.id}" title="{ts}Group Contacts{/ts}">{ts}Contacts{/ts}</a>
                |
                <a href="{crmURL p='civicrm/group' q='reset=1&action=update&id='}{$subgroup.id}" title="{ts}Edit Group{/ts}">{ts}Settings{/ts}</a>
              </td>
            </tr>
          {/foreach}
        </tbody>
      </table>
      <br />
    </div>
  </div>
{/if}

{literal}
  <script type="text/javascript">
    cj(document).ready(function() {
      cj('#subgroups-table').dataTable({
        // Default to ordering by subgroup ID
        'aaSorting':      [[ 1, 'asc' ]],
        'iDisplayLength': 25,
        'aoColumnDefs':   [
          // Sorting by the actions column doesn't make sense
          {'bSortable': false, 'aTargets': [4]}
        ]
      });
    });
  </script>
{/literal}
