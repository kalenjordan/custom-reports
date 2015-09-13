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
- Allan MacGregor
- Fabrizio Branca
- Kalen Jordan
- Lee Saferite
- Tom Steigerwald
- Rolando Granadino
- Cyrill Schumacher

#### **Known Issues**
- Calendar Chart only supports one year.

#### **Disclaimer**
 - **Use at your own risk.**
 - **This is a developer tool.**
 - **We know you can delete from tables.**

#### **Grid Configuration Format**

It's possible to make columns filterable by using the "Grid Configuraiton" option. This field expects a JSON oject with key/value pairs.
There's two options to make a set of columns configurable, an array containing the names of the columns to be filtered:

```
{
"filterable": ["customer_group", "region"]
}
```
Or an object with key/value pairs of the column name and Magento admin block type. It is important that this be a valid block type otherwise the grid will fail to render.
```
{
"filterable": {"created_at_date": "adminhtml/widget_grid_column_filter_date"}
}
```
Here is a list of common filter block types:
* `adminhtml/widget_grid_column_filter_datetime`
* `adminhtml/widget_grid_column_filter_date`
* `adminhtml/widget_grid_column_filter_range`
* `adminhtml/widget_grid_column_filter_country`

More can be found in `app/code/core/Magento/Adminhtml/Block/Widget/Grid/Column.php` within the `_getFilterByType` method.

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
