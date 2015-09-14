<?php
 
$rows = array(
    array(
        'sql_query' 	=> "SELECT UNIX_TIMESTAMP(date(customer_entity.created_at)) AS 'Created At', COUNT(entity_id) AS 'Customers' FROM customer_entity GROUP BY DAY(customer_entity.created_at)",
        'title' 		=> 'Sample: New Customers',
        'created_at' 	=> NULL,
        'output_type' 	=> 'Calendar',
        'chart_config' 	=> '{height: 500, title: "New Customers", calendar: {cellSize: 20}}',
        'grid_config' 	=> '{"filterable": {"Customers": "adminhtml/widget_grid_column_filter_range"}}'
    ),
    array(
        'sql_query' 	=> "SELECT sales_order_status.label AS 'Status', COUNT(sales_flat_order.entity_id) AS 'Orders' FROM sales_flat_order LEFT JOIN sales_order_status ON sales_flat_order.status = sales_order_status.status GROUP BY sales_flat_order.status ORDER BY COUNT(sales_flat_order.entity_id) DESC",
        'title' 		=> 'Sample: Order Status',
        'created_at' 	=> NULL,
        'output_type' 	=> 'PieChart',
        'chart_config' 	=> '{height: 900, width: 1100, title: "Order Status"}',
        'grid_config' 	=> '{"filterable": {"Status": "adminhtml/widget_grid_column_filter_text", "Orders": "adminhtml/widget_grid_column_filter_range"}}'
    ),
    array(
        'sql_query' 	=> "SELECT CONCAT(YEAR(sales_flat_order.created_at), ' - ', MONTHNAME(sales_flat_order.created_at)) AS 'Month', SUM(IF(`status` = 'canceled', 1, 0)) AS 'Canceled', SUM(IF(`status` = 'closed', 1, 0)) AS 'Closed', SUM(IF(`status` = 'complete', 1, 0)) AS 'Complete', SUM(IF(`status` = 'complete_partially_shipped', 1, 0)) AS 'Partially Shipped', SUM(IF(`status` = 'processing', 1, 0)) AS 'Processing', SUM(IF(`status` = 'shipped', 1, 0)) AS 'Shipped' FROM sales_flat_order GROUP BY MONTH(sales_flat_order.created_at)",
        'title' 		=> 'Sample: Order Status by Month',
        'created_at' 	=> NULL,
        'output_type' 	=> 'ColumnChart',
        'chart_config' 	=> '{isStacked: true, height: 600, width: 1400, title: "Order Status by Month", vAxis: {title: "Number of orders"}}',
        'grid_config' 	=> '{"filterable": {"Canceled": "adminhtml/widget_grid_column_filter_range", "Closed": "adminhtml/widget_grid_column_filter_range", "Complete": "adminhtml/widget_grid_column_filter_range", "Partially Shipped": "adminhtml/widget_grid_column_filter_range", "Processing": "adminhtml/widget_grid_column_filter_range", "Shipped": "adminhtml/widget_grid_column_filter_range"}}'
    ),
    array(
        'sql_query' 	=> "SELECT UNIX_TIMESTAMP(date(sales_flat_order.created_at)) AS 'Created At', COUNT(entity_id) AS 'Orders' FROM sales_flat_order WHERE YEAR(sales_flat_order.created_at) = YEAR(CURDATE()) GROUP BY DAY(sales_flat_order.created_at) ORDER BY sales_flat_order.created_at",
        'title' 		=> 'Sample: Sales by Day',
        'created_at' 	=> NULL,
        'output_type' 	=> 'Calendar',
        'chart_config' 	=> '{height: 500, title: "Sales by Day", calendar: {cellSize: 20}}',
        'grid_config' 	=> '{"filterable": {"Orders": "adminhtml/widget_grid_column_filter_range"}}'
    ),
    array(
        'sql_query' 	=> "SELECT CONCAT(YEAR(sales_flat_order.created_at), ' - ', MONTHNAME(sales_flat_order.created_at)) AS 'Month', TRUNCATE(SUM(base_grand_total), 2) AS 'Total', TRUNCATE(SUM(base_total_canceled), 2) AS 'Canceled' FROM sales_flat_order GROUP BY MONTH(sales_flat_order.created_at)",
        'title' 		=> 'Sample: Sales by Month',
        'created_at' 	=> NULL,
        'output_type' 	=> 'ColumnChart',
        'chart_config' 	=> '{isStacked: true, height: 600, width: 1400, title: "Sales by Month", vAxis: {title: "Sales value"}}',
        'grid_config' 	=> '{"filterable": {"Total": "adminhtml/widget_grid_column_filter_range", "Canceled": "adminhtml/widget_grid_column_filter_range"}}'
    ),
    array(
        'sql_query' 	=> "SELECT CONCAT(YEAR(sales_flat_order.created_at), ' - ', MONTHNAME(sales_flat_order.created_at)) AS 'Month', TRUNCATE(SUM(base_grand_total), 2) AS 'Total', TRUNCATE(SUM(base_total_canceled), 2) AS 'Canceled' FROM sales_flat_order GROUP BY MONTH(sales_flat_order.created_at)",
        'title' 		=> 'Sample: Sales by Month',
        'created_at' 	=> NULL,
        'output_type' 	=> 'BarChart',
        'chart_config' 	=> '{isStacked: true, height: 600, width: 1400, title: "Sales by Month", hAxis: {title: "Sales value"}}',
        'grid_config' 	=> '{"filterable": {"Total": "adminhtml/widget_grid_column_filter_range", "Canceled": "adminhtml/widget_grid_column_filter_range"}}'
    ),
);

$resource = Mage::getSingleton('core/resource');
$table = $resource->getTableName('cleansql_report');
$resource->getConnection('core_write')->insertMultiple($table, $rows);
