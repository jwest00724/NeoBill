<div class="manager_content"</div>
<h2>{echo phrase="FULFILLED_ORDERS"}</h2>

<div class="table">
  {form name="fulfilled_orders"}
    {form_table field="orders" size="10"}

      {form_table_column columnid="id" header="[ID]"}
        <a target="content" href="manager_content.php?page=view_order&order={$orders.id}">{$orders.id}</a>
      {/form_table_column}

      {form_table_column columnid="contactname" header="[CUSTOMER]"}
        {$orders.contactname}
      {/form_table_column}

      {form_table_column columnid="accounttype" header="[ACCOUNT]"}
        <a href="manager_content.php?page=accounts_view_account&account={$orders.accountid}">{$orders.accountname}</a>
      {/form_table_column}

      {form_table_column columnid="datecompleted" header="[ORDER_DATE]"}
        {$orders.datecompleted|datetime}
      {/form_table_column}

      {form_table_column columnid="datefulfilled" header="[DATE_FULFILLED]"}
        {$orders.datefulfilled|datetime}
      {/form_table_column}

      {form_table_column columnid="remoteip" header="[ORDER_IP]"}
        {$orders.remoteipstring}
      {/form_table_column}

      {form_table_column columnid="total" header="[ORDER_TOTAL]"}
        {$orders.total|currency}
      {/form_table_column}

    {/form_table}
  {/form}
</div>