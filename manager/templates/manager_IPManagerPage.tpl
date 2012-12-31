<div class="manager_content"</div>
<h2>{echo phrase="IP_ADDRESS_POOL"}</h2>
<div class="search">
  {form name="search_ips"}
    <table>
      <tr>
        <th> {echo phrase="SEARCH"} </th>
        <td>
          {form_description field="ipaddressstring"} <br/>
          {form_element field="ipaddressstring" size="15"}
        </td>
        <td>
          {form_description field="hostname"} <br/>
          {form_element field="hostname" size="20"}
        </td>
        <td>
          {form_description field="accountname"} <br/>
          {form_element field="accountname" size="20"}
        </td>
        <td>
          {form_description field="service"} <br/>
          {form_element field="service" size="20"}
        </td>
        <td class="submit"> 
          {form_element field="search"}
        </td>
      </tr>
    </table>
  {/form}
</div>

<div class="table">
  {form name="ippool"}
    {form_table field="ipaddresses" size="20"}

      {form_table_column columnid=""}
	{form_table_checkbox option=$ipaddresses.ipaddress}
      {/form_table_column}

      {form_table_column columnid="ipaddress" header="[IP_ADDRESS]"}
        {$ipaddresses.ipaddressstring}
      {/form_table_column}

      {form_table_column columnid="server" header="[SERVER]"}
        <a href="manager_content.php?page=services_view_server&server={$ipaddresses.server}">{$ipaddresses.hostname}</a>
      {/form_table_column}

      {form_table_column columnid="accountname" header="[ASSIGNED_TO]"}
        {if $ipaddresses.isAvailable}
          [AVAILABLE]
        {else}
          <a href="manager_content.php?page=accounts_view_account&account={$ipaddresses.accountid}">{$ipaddresses.accountname}</a>
        {/if}
      {/form_table_column}

      {form_table_column columnid="service" header="[SERVICE]"}
        {if $ipaddresses.isAvailable}
          [N/A]
        {else}
          {$ipaddresses.service}
        {/if}
      {/form_table_column}

      {form_table_footer}
        {form_element field="remove"}
      {/form_table_footer}

    {/form_table}
  {/form}
</div>
