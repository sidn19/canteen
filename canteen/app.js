
$(document).ready(function(){
  $.ajax({
    url: "./data.php",
    method: "GET",
    success: function(data) {
      var date = [];
      var amount = [];
      for(var i in data) {
        date.push(data[i].date);
        amount.push(-data[i].amount);
      }

      var chartdata = {
        labels: date,
        datasets : [
          {
            label: 'Earnings Amount',
            backgroundColor: 'rgba(255, 0, 132, 0.9)',
            borderColor: 'rgba(200, 200, 200, 0.75)',
            hoverBackgroundColor: 'rgba(200, 200, 200, 1)',
            hoverBorderColor: 'rgba(200, 200, 200, 1)',
            data: amount
          }
        ]
      };

      var ctx = $("#mycanvas1");

      var barGraph = new Chart(ctx, {
        type: 'bar',
        data: chartdata
      });
    },
    error: function(data) {
      console.log(data);
    }
  });
  $.ajax({
    url: "./piedata.php",
    method: "GET",
    success: function(data) {
      var item = [];
      var quantity = [];
      var colors = [];
      for(var i in data) {
        item.push(data[i].name);
        quantity.push(data[i].quantity);
        colors.push(color());
      }

      var chartdata = {
        labels: item,
        datasets : [
          {
            label: 'Food Item sales',
            backgroundColor: colors,
            borderColor: 'rgba(200, 200, 200, 0.75)',
            hoverBackgroundColor: 'rgba(200, 200, 200, 1)',
            hoverBorderColor: 'rgba(200, 200, 200, 1)',
            data: quantity
          }
        ]
      };

      var ctx = $("#mycanvas2");

      var pieGraph = new Chart(ctx, {
        type: 'pie',
        data: chartdata
      });
    },
    error: function(data) {
      console.log(data);
    }
  });
  function color() {
        var r = Math.floor(Math.random()*256);
        var g = Math.floor(Math.random()*256);
        var b = Math.floor(Math.random()*256);
        var rgba = 'rgba('+r+','+g+','+b+',1.0)'
        return rgba;
      }
});

