<?php
error_reporting(0);
/* * *** INCLUDE CONNECTION FILE *********************************************************************************** */
include_once('../config.php');
$_SESSION['TOPMENU'] = "author";
$modelObj = new Author();
$db = Database::Instance();
$id = $_GET['searchByAuthor'];

$query = "SELECT COUNT(id) as cnt, month(insertdate) as month,year(insertdate) as year FROM `content` where author_id=" . $id . "  GROUP BY month(insertdate),year(insertdate) order by year(insertdate) desc,month(insertdate) desc limit 12";
$db->query($query);
if ($db->getRowCount() > 0)
  $result_data = $db->getResultSet();
$totalcnt = 0;
foreach ($result_data as $val) {
  $totalcnt = $totalcnt + $val['cnt'];
}
?>
<!DOCTYPE HTML>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script>
    <script type="text/javascript" src="<?php echo JSFILEPATH; ?>/highcharts.js"></script>
    <title>Author Statistics</title>
    <link href="<?php echo CSSFILEPATH; ?>/cms.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo CSSFILEPATH; ?>/popup.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript">
		
      var chart;
      $(document).ready(function() {
        chart = new Highcharts.Chart({
          chart: {
            renderTo: 'containermonth',
            defaultSeriesType: 'column',
            // width: 500,
            height: 300
          },
          title: {
            text: 'Monthly Report'
          },
          xAxis: {
            categories: [
                          <?php
                          foreach ($result_data as $val) {
                            echo "'" . date("M", mktime(0, 0, 0, ($val['month']))) . "," . $val['year'] . "',";
                          }
                          ?>
                           ]
                         },
                         yAxis: {
                           min: 0,
                           title: {
                             text: 'Number of stories written'
                           }
                         },
                         legend: {
                           layout: 'vertical',
                           //backgroundColor: Highcharts.theme.legendBackgroundColor || '#FFFFFF',
                           align: 'left',
                           verticalAlign: 'top',
                           y: -10,
                           floating: true,
                           shadow: true
                         },
                         tooltip: {
                           formatter: function() {
                             return ''+
                               this.x +': '+ this.y +' stories';
                           }
                         },
                         plotOptions: {
                           column: {
                             pointPadding: 0.2,
                             borderWidth: 0
                           },
                           series: {
                             cursor: 'pointer',
                             events: {
                               click: function(event) {load_chart(<?php echo $id; ?>,event.point.category);		
                               }
                             }
                           }
                         },
                         series: [{name: 'Submitted stories',
                             data: [<?php foreach ($result_data as $val) {
  echo $val['cnt'] . ",";
} ?>]
   
                           }]
                       });
   
   
                     });

                     function load_chart(id,date){
                       var newchart;
                       var options = {

                         chart: {
                           renderTo: 'container1',
                           height: 230
                         },

                         title: {
                           text: 'weekly submitted stories'
                         },

                         xAxis: {
                           type: 'datetime',
                           tickInterval: 7 * 24 * 3600 * 1000, // one week
                           tickWidth: 0,
                           gridLineWidth: 1,
                           labels: {
                             align: 'left',
                             x: 3,
                             y: -3
                           }
                         },

                         yAxis: [{ // left y axis
                             title: {
                               text: null
                             },
                             labels: {
                               align: 'left',
                               x: 3,
                               y: 16,
                               formatter: function() {
                                 return Highcharts.numberFormat(this.value, 0);
                               }
                             },
                             showFirstLabel: false
                           }, { // right y axis
                             linkedTo: 0,
                             gridLineWidth: 0,
                             opposite: true,
                             title: {
                               text: null
                             },
                             labels: {
                               align: 'right',
                               x: -3,
                               y: 16,
                               formatter: function() {
                                 return Highcharts.numberFormat(this.value, 0);
                               }
                             },
                             showFirstLabel: false
                           }],

                         legend: {
                           align: 'left',
                           verticalAlign: 'top',
                           y: -10,
                           floating: true,
                           borderWidth: 0
                         },

                         tooltip: {
                           shared: true,
                           crosshairs: true
                         },

                         plotOptions: {
                           series: {
                             cursor: 'pointer',
                             point: {
                               events: {
                                 click: function() {

                                   hs.htmlExpand(null, {
                                     pageOrigin: {
                                       x: this.pageX,
                                       y: this.pageY
                                     },
                                     headingText: this.series.name,
                                     maincontentText: Highcharts.dateFormat('%b %e, %Y', this.x) +':<br/> '+
                                       this.y +' visits',
                                     width: 200
                                   });
                                 }
                               }
                             },
                             marker: {
                               lineWidth: 1
                             }
                           }
                         },

                         series: [{
                             name: 'Submitted stories',
                             lineWidth: 2,
                             marker: {
                               radius: 4
                             }
                           }]
                       };
                       $.ajax({
                         type: "POST",
                         url: "getauthor.php",
                         data: 'action=graph&authorid='+id+'&date='+date,
                         success: function(resultdata){
                           allVisits = [];
                           var jObj=JSON.parse(resultdata);
                           for (var name in jObj) {
                             date = Date.parse(jObj[name].date +' UTC');
                             allVisits.push([
                               date,
                               parseInt(jObj[name].cnt)
                             ]);
                           }
                           options.series[0].data = allVisits;
                           //options.series[1].data = newVisitors;

                           newchart = new Highcharts.Chart(options);
                         }
                       });

                     }

				
    </script>

  </head>
  <body>
    <div id="containermonth">
    </div>
    <div id="container1">
    </div>
  </body>
</html>