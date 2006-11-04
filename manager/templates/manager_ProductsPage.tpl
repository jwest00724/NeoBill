<div class="action">
  <p class="header">{echo phrase="ACTIONS"}</p>
  {form name="products_action"}
    {form_element field="add"}
  {/form}
</div>

<h2> {echo phrase="PRODUCTS"} </h2>
<div class="search">
  {form name="search_productdbo_table"}
    <table>
      <tr>
        <th> {echo phrase="SEARCH"} </th>
        <td>
          {form_description field="id"} <br/>
          {form_element field="id" size="4"}
        </td>
        <td>
          {form_description field="name"} <br/>
          {form_element field="name" size="30"}
        </td>
        <td>
          {form_description field="description"} <br/>
          {form_element field="description" size="30"}
        </td>
        <td class="submit"> 
          {form_element field="search"}
        </td>
      </tr>
    </table>
  {/form}
</div>

<div class="table">
  {form name="products"}
    {form_table field="products" size="10"}

      {form_table_column columnid="id" header="[ID]"}
        <a target="content" href="manager_content.php?page=services_view_product&product={$products.id}"> {$products.id} </a>
      {/form_table_column}

      {form_table_column columnid="name" header="[PRODUCT_NAME]"}
        <a target="content" href="manager_content.php?page=services_view_product&product={$products.id}"> {$products.name} </a>
      {/form_table_column}

      {form_table_column columnid="price" header="[PRICE]"}
        {$products.price|currency}
      {/form_table_column}

      {form_table_column columnid="taxable" header="[TAXABLE]"}
        {$products.taxable}
      {/form_table_column}
    
    {/form_table}
  {/form}
</div>