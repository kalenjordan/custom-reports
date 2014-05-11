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

#### **Contributors**
Kalen Jordan
Allan MacGregor
Fabrizio Branca
Lee Saferite
Tom Steigerwald


#### **Known Issues**
- Calendar Chart only supports one year.

#### **Disclaimer** 
 - **Use at your own risk.**
 - **This is a developer tool.** 
 - **We know you can drop tables.**

#### 

  [1]: https://developers.google.com/chart/
  [2]: https://developers.google.com/chart/interactive/docs/gallery/piechart
  [3]: https://developers.google.com/chart/interactive/docs/gallery/barchart
  [4]: https://developers.google.com/chart/interactive/docs/gallery/columnchart
  [5]: https://developers.google.com/chart/interactive/docs/gallery/calendar
  [6]: http://dev.mysql.com/doc/refman/5.1/en/date-and-time-functions.html#function_unix-timestamp