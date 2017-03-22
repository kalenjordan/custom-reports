# **Custom Magento Reports with Google Chart API**

Easily create reports with custom SQL queries and display them using the magento admin grid or using [Google Charts API][1].

### **Features**
- Report result table
 -  Ability to define column configuration or allow auto configuration.
- The following Google Charts are currently supported, please see the examples:
 - [Pie Chart][2]
 - [Bar Chart][3]
 - [Column Chart][4]
 - [Calendar Chart][5]
     - When querying for the date, you must query the date using [UNIX_TIMESTAMP()][6] like the following:
         - `UNIX_TIMESTAMP(date([[date field]]))`
- Control access to viewing, editing, and creation of reports in Magento ACL list.
- Cache results in dynamically created tables for performance and historical reasons.
- Select separate database connection to run queries against
- Ability to add links to column values (clickable)
- Ability to hide columns when data is used for other columns (such as clickable)

#### **TO DO**
- Fix the calendar chart to support more than 1 year.
- Add logic to prepare data for specific chart types.
- Wrapping the json results for web service consumption.
- Add the sample reports
 - New Customers (Calendar Chart)
 - Order Status (Pie Chart)
 - Order Status By Month (Stacked column chart)
 - Sales by Month (Bar Chart)
 - Sales By Day (Calendar Chart)
 - Sales by Month (Column Chart)
- Add ability to select database resource per report (?)
- Better documentation...

#### **Contributors**
See [the contributor list](https://github.com/kalenjordan/custom-reports/graphs/contributors)

#### **Known Issues**
- Calendar Chart only supports one year.
- "currency" column type uses the default store currency, so currency symbol can be wrong on a multiple currency store.

#### **Disclaimer**
 - **Use at your own risk.**
 - **This is a developer tool.**
 - **We know you can delete from tables.**

#### **Grid Configuration Format**

It's possible to make columns filterable by using the "Grid Configuration" option. This field expects a JSON object with key/value pairs.
There are two options to make a set of columns configurable, an array containing the names of the columns to be filtered:

```json
{
"filterable": ["customer_group", "region"]
}
```

Or an object with key/value pairs of the column name and Magento admin block type. It is important that this be a valid block type otherwise the grid will fail to render.

```json
{
"filterable": {"created_at_date": "adminhtml/widget_grid_column_filter_date"}
}
```
Here is a list of common filter block types:
* `adminhtml/widget_grid_column_filter_datetime`
* `adminhtml/widget_grid_column_filter_date`
* `adminhtml/widget_grid_column_filter_range`
* `adminhtml/widget_grid_column_filter_country`

More can be found in `app/code/core/Mage/Adminhtml/Block/Widget/Grid/Column.php` within the `_getFilterByType` method.

The column data can be rendered using the Magento types:

```json
{  
   "type":{  
      "order_date":"date",
      "total_price":"currency",
   }
}
```

You can also create clickable row values, hide columns and define alignment. Example;

```json
{  
   "clickable":{  
      "order_id":{  
         "*/sales_order/view/order_id":"order_id"
      },
      "sku":{  
         "*/catalog_product/edit/id":"product_id"
      }
   },
   "hidden":{  
      "product_id":true
   },
   "alignment":{
      "sku":"center",
      "total_price":"right"
   }
}
```

### Export
You can export the plain tables to CSV or Excel. By default, is used the standard Magento export mechanism.

If you want to export to Excel 2007 format, follow next steps:
 - Download PHPExcel library from https://github.com/PHPOffice/PHPExcel
 - Create the folder lib/PHPExcel
 - Copy the downloaded Classes folder to lib/PHPExcel

If the library is found, then it will be used instead the default Magento export. In that case, cells will adjust automatically to content and the alignment definition allows to align columns in the Excel.

### License
The license is currently <a href="https://tldrlegal.com/license/creative-commons-attribution-noncommercial-(cc-nc)#summary">Creative Commons Attribution NonCommercial</a>.  TL;DR is that you can modify and distribute but not for commercial use.

Changed it on July 24 from OSL to this license because there was a company that started distributing it in a way that didn't seem kosher.  If you're legitimately interested in commercially redistributing it, I'd probably be fine with that - just get in touch.

####

  [1]: https://developers.google.com/chart/
  [2]: https://developers.google.com/chart/interactive/docs/gallery/piechart
  [3]: https://developers.google.com/chart/interactive/docs/gallery/barchart
  [4]: https://developers.google.com/chart/interactive/docs/gallery/columnchart
  [5]: https://developers.google.com/chart/interactive/docs/gallery/calendar
  [6]: http://dev.mysql.com/doc/refman/5.1/en/date-and-time-functions.html#function_unix-timestamp
