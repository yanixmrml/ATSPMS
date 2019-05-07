$(document).ready(function(){
    
    /****************************** Power Management *******************************/
    var load_info_delay = 3000;
    var num_tick = 5;
    var historical_selection = ["Power (kW)", "Voltage (V)", "Current (A)", "Frequency (Hz)", "Power Interruptions"];
    
    
    Highcharts.setOptions({
      global: {
            useUTC: false
      }
    });

    Highcharts.chart('realtime-reports-container', {
      chart: {
            type: 'spline',
            animation: Highcharts.svg, // don't animate in old IE
            marginRight: 10,
            events: {
              load: function () {
                    // set up the updating of the chart each second
                    var realtimeSeries = this.series[0];
                    var powerGoalSeries = this.series[1];
                    console.log(this.title);
                    //this.setTitle({text: "SDsdsdD"}); - Changing Title
                    var src = $("#update-realtime-summary").attr("action");
                    setInterval(function () {
                        var ave_power = 0;
                        var power_goal = 0;
                        $.ajax({
                            url: src,
                            type: 'POST',
                            data: {},
                            dataType: 'json',
                            beforeSend: function(){
                                //$("#dynamicMessage-settings").find('.modal-body p').html("Please wait while the mains and secondary information are loading...");
                            },
                            success: function(data,textStatus,jqXHR){
                                        if(data!=null && data!=""){
                                                ave_power = data["total_power_so_far"]
                                                power_goal = parseFloat($("#power_goal").val());
                                                
                                                var x = (new Date()).getTime();//, // current time
                                                
                                                realtimeSeries.addPoint([x, parseFloat((ave_power/1000).toFixed(3))], true, true);
                                                powerGoalSeries.addPoint([x, parseFloat((power_goal/1000).toFixed(3))], true, true);
                                                
                                        }
                            },
                            error: function(jqXHR, textStatus, errorThrown){
                                $("#reportsMessageModal").find('.modal-body p').html(textStatus + ": " + errorThrown);
                                $("#reportsMessageModal").modal("show");
                            },
                            complete: function(){
                                //$("#dynamicMessage-settings").html("");
                            }
                        });                     
                        
                    }, 1500);
              }
            }
      },
      title: {
            text: 'Realtime Real Power Consumption'
      },
      credits: {
          enabled: false
      },
      xAxis: {
            type: 'datetime',
            tickPixelInterval: 150
      },
      yAxis: {
            title: {
              text: 'Power (kW)'
            },
            plotLines: [{
              value: 0,
              width: 1,
              color: '#808080'
            }]
      },
      tooltip: {
            formatter: function () {
              return '<b>' + this.series.name + '</b><br/>' +
                    Highcharts.dateFormat('%Y-%m-%d %H:%M:%S', this.x) + '<br/>' +
                    Highcharts.numberFormat(this.y, 2);
            }
      },
      legend: {
            enabled: true
      },
      exporting: {
            enabled: true
      },
      series: [{
            name: 'Commulative Real Power Since Sart of the Month (kW)',
            data: (function () {
              // generate an array of random data
              var data = [],
                    time = (new Date()).getTime(),
                    i;
              var y_data;  
              for (i = -19; i <= 0; i += 1) {
                    data.push({
                      x: time + i * 1000,
                      y: y_data
                    });
              }
              return data;
            }())
        },
            {
            name: 'Real Power Goal (kW)',
            data: (function () {
              // generate an array of random data
              var data = [],
                    time = (new Date()).getTime(),
                    i;
              var y_data;  
              for (i = -19; i <= 0; i += 1) {
                    data.push({
                      x: time + i * 1000,
                      y: y_data
                    });
              }
              return data;
            }())
        }]
    });
    
    setInterval(function () {
        var src = $("#update-load-summary").attr("action");
        var ave_power = 0;
        $.ajax({
            url: src,
            type: 'POST',
            data: {},
            dataType: 'json',
            beforeSend: function(){
                //$("#dynamicMessage-settings").find('.modal-body p').html("Please wait while the mains and secondary information are loading...");
            },
            success: function(data,textStatus,jqXHR){
                        if(data!=null && data!=""){;
                                var voltage = parseFloat(data["voltage"]);
                                var current = parseFloat(data["current"]);
                                var frequency = parseFloat(data["frequency"]);
                                ave_power = parseFloat(data["power"]);
                                var status = parseInt(data["status"])==1?"Stable":"Unstable";
                                var source = parseInt(data["source_id"])==1?"MAINS":"SECONDARY";
                                var power_factor = 0;
                                if(current>0 && voltage>0){
                                    power_factor = ((ave_power)/(current*voltage));   
                                }
                                var apparent_power = ((voltage * current)/1000);
                                var is_auto_load_shedding = parseInt(data["is_auto_load_shedding"]);
                                var mode_selection = parseInt(data["is_manual_selection"])==0?"ATS is enabled":"Manual Transfer is enabled";
                                
                                //Information
                                $("#load-summary-info-body").html("Mode: <b>" + mode_selection + "</b><br/>" +
                                                                  "Source: <b>" + source + "</b><br/>" +
                                                                                "Status : <b>" + status + "</b><br/>" +
                                                "Frequency : <b>" + frequency.toFixed(2) + " Hz</b><br/>" + 
                                                                                "Voltage : <b>" + voltage.toFixed(2) + " V</b><br/>" + 
                                                "Current : <b>" + current.toFixed(2) + " A</b><br/>" +
                                                                                "Apparent Power : <b>" + apparent_power.toFixed(3) + " kVA</b><br/>" + 
                                                                                "Average Power : <b>" + (ave_power/1000).toFixed(3) + " kW</b><br/>" +
                                                                                "Power Factor : <b>" + power_factor.toFixed(3) + "</b><br/>");
                                if(is_auto_load_shedding){
                                    $("#auto-load-shedding-label").html("Auto Load Shedding is&nbsp;<b>ON</b>");
                                }else{
                                    $("#auto-load-shedding-label").html("Auto Load Shedding is&nbsp;<b>OFF</b>");
                                }
                                
                        }
            },
            error: function(jqXHR, textStatus, errorThrown){
                $("#reportsMessageModal").find('.modal-body p').html(textStatus + ": " + errorThrown);
                $("#reportsMessageModal").modal("show");
            },
            complete: function(){
                //$("#dynamicMessage-settings").html("");
            }
        });                     
        
    }, 2000);
    
    
    setInterval(function () {
        var src = $("#update-power-interruptions").attr("action");
        $.ajax({
            url: src,
            type: 'POST',
            data: {},
            dataType: 'html',
            beforeSend: function(){
                //$("#dynamicMessage-settings").find('.modal-body p').html("Please wait while the mains and secondary information are loading...");
            },
            success: function(data,textStatus,jqXHR){
                        if(data!=null && data!=""){;
                            //Information
                            $("#power-interruptions-data").html(data);                                
                        }
            },
            error: function(jqXHR, textStatus, errorThrown){
                $("#reportsMessageModal").find('.modal-body p').html(textStatus + ": " + errorThrown);
                $("#reportsMessageModal").modal("show");
            },
            complete: function(){
                //$("#dynamicMessage-settings").html("");
            }
        });                     
        
    },4000);
    
    var monthlyCostOptions = {
      chart: {
        type: 'solidgauge'
      },
      title: null,
      pane: {
        center: ['50%', '65%'],
        size: '100%',
        startAngle: -90,
        endAngle: 90,
        background: {
          backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || '#EEE',
          innerRadius: '60%',
          outerRadius: '100%',
          shape: 'arc'
        }
      },
    
      tooltip: {
        enabled: true
      },
    
      // the value axis
      yAxis: {
        stops: [
          [0.1, '#55BF3B'], // green
          [0.85, '#DF5353'] // red
        ],
        lineWidth: 0,
        minorTickInterval: null,
        tickAmount: num_tick,
        title: {
          y: 70
        },
        labels: {
          y: -3
        }
      },
    
      plotOptions: {
        solidgauge: {
          dataLabels: {
            y : 6,
            borderWidth: 0,
            useHTML: true
          }
        }
      }
    };
    
    var chartMonthlyCost = Highcharts.chart('monthly-cost', Highcharts.merge(monthlyCostOptions, {
        yAxis: {
          min: 0,
          max: parseFloat($("#cost_goal").val()),
          title: {
            text: '<b>MONTHLY COST (Php)</b>'
          }
        },
        
        credits: {
          enabled: false
        },
        
        series: [{
          name: 'Communlative Monthly Cost',
          data: [0],
          dataLabels: {
            format: '<div style="text-align:center"><span style="font-size:25px;color:' +
              ((Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black') + '">{y:.2f}</span><br/>' +
                 '<span style="font-size:12px;color:silver">-</span></div>'
          },
          tooltip: {
            valueSuffix: ''
          }
        }]
      
    }));
    
    setInterval(function () {
        var point;
        var src = $("#get-monthly-cost").attr("action");
	$.ajax({
	    url: src,
	    type: 'POST',
	    data: {c_pkwh: $("#cost_pkwh").val()},
	    dataType: 'json',
	    beforeSend: function(){
		//$("#dynamicMessage-settings").find('.modal-body p').html("Please wait while the mains and secondary information are loading...");
	    },
	    success: function(data,textStatus,jqXHR){
		if(data!=null && data!=""){
                    var monthly_cost = data["total_cost"];
                    
                    // secondary Power Factor
                    if (chartMonthlyCost) {
                        point = chartMonthlyCost.series[0].points[0];
                        point = chartMonthlyCost.series[0].points[0];
                        point.update(parseFloat(monthly_cost.toFixed(3)));
                    }
		}
	    },
	    error: function(jqXHR, textStatus, errorThrown){
		$("#reportsMessageModal").find('.modal-body p').html(textStatus + ": " + errorThrown);
		$("#reportsMessageModal").modal("show");
	    },
	    complete: function(){
		//$("#dynamicMessage-settings").html("");
	    }
	});
    },1000);

    Highcharts.chart('historical-reports-container', {
      chart: {
            type: 'spline',
            animation: Highcharts.svg, // don't animate in old IE
            marginRight: 10,
            events: {
              load: function () {
                    // set up the updating of the chart each second
                    var chart = this;
                    var historicalSeries = this.series[0];
                    var src = $("#update-realtime-summary").attr("action");
                    $("#update-data-button").on("click",function () {
                        var ndx = parseInt($("#data-type").val());
                        chart.setTitle({text: historical_selection[ndx]}); 
                        
                        /*var ave_power = 0;
                        var power_goal = 0;*/
                        
                        $.ajax({
                            url: src,
                            type: 'POST',
                            data: {},
                            dataType: 'json',
                            beforeSend: function(){
                                //$("#dynamicMessage-settings").find('.modal-body p').html("Please wait while the mains and secondary information are loading...");
                            },
                            success: function(data,textStatus,jqXHR){
                                        if(data!=null && data!=""){
                                               for(var i=0;i<data.length;i++){
                                                    historicalSeries.addPoint(data[i]["datetime"],parseFloat(data[i][])],true,true);     
                                               }
                                        }
                            },
                            error: function(jqXHR, textStatus, errorThrown){
                                $("#reportsMessageModal").find('.modal-body p').html(textStatus + ": " + errorThrown);
                                $("#reportsMessageModal").modal("show");
                            },
                            complete: function(){
                                //$("#dynamicMessage-settings").html("");
                            }
                            
                        });                    
                        
                    });
              }
            }
      },
      title: {
            text: 'Historical Data'
      },
      credits: {
          enabled: false
      },
      xAxis: {
            type: 'datetime',
            tickPixelInterval: 150
      },
      yAxis: {
            title: {
              text: 'Select Data'
            },
            plotLines: [{
              value: 0,
              width: 1,
              color: '#808080'
            }]
      },
      tooltip: {
            formatter: function () {
              return '<b>' + this.series.name + '</b><br/>' +
                    Highcharts.dateFormat('%Y-%m-%d %H:%M:%S', this.x) + '<br/>' +
                    Highcharts.numberFormat(this.y, 2);
            }
      },
      legend: {
            enabled: true
      },
      exporting: {
            enabled: true
      },
      series: [{
            name: 'Select Type of Data',
            data: (function () {
              // generate an array of random data
              var data = [];
              for (i = -19; i <= 0; i += 1) {
                    data.push({
                      x: 0,
                      y: 0
                    });
              }
              return data;
                
            }())
        }]
    });

});