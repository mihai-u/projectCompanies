@extends('layouts.layout')

@section('title')
Report
@endsection

@section('links')
    <link rel="stylesheet" type="text/css" href="/css/reportShow.css">
    <!-- <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css"> -->
    
@endsection

@section('content')
<h1 style="text-align: center;" class="mt-5">Total Hours Worked</h1>


<div class="container pieChartContainer mt-5">
  <div id="pieChart"></div>
</div>

{{-- Start of filters interface --}}
<div class="formContainer">
<form class="mt-5 mb-2">
  <div class="form-row align-items-center ml-4">
    <div class="col col-xs-12 col-sm-3 my-1">
      <label class="sr-only" for="inlineFormInputName">Company</label>
      <div class="dropdown companyDropdown">
            <select class="form-control" id="companyDropdown" required>
              <option>Select Company</option>

              @foreach($companies as $company)
                <option value="{{ $company->id }}"> {{ $company->companyName }} </option>
              @endforeach
            </select>
      </div>
    </div>
    <div class="date-col col-xs-12 col-sm-3 my-1">
      <label class="sr-only" for="inlineFormInputGroupUsername">From</label>
      <div class="input-group">
        <div class="input-group-prepend">
          <div class="input-group-text">From</div>
        </div>
        <input type="date" name="from" id="from" class="form-control">
      </div>
    </div>

    <div class="date-col col-xs-12 col-sm-3 my-1">
      <label class="sr-only" for="inlineFormInputGroupUsername">To</label>
      <div class="input-group">
        <div class="input-group-prepend">
          <div class="input-group-text">To</div>
        </div>
        <input type="date" name="to" id="to" class="form-control">
      </div>
    </div>

    <div class="col-auto my-1">
      <a class="btn btn-primary" role="button" href="#" onclick="calculateTimeByEmployee()"> Show </a>
    </div>
  </div>
</form>
</div>

{{-- End of filters interface --}}







<div class="container-fluid" id="main">
    {{-- Start of Table --}}
        <table class="table">
                <thead class="thead-dark">
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Project</th>
                    <th scope="col">Task</th>
                    <th scope="col">Worked Time</th>
                    <th scope="col">Action</th>
                  </tr>
                </thead>
                <tbody id="tasksTableBody">


                </tbody>
        </table>
    {{-- End of Table --}}

    {{-- Start of Model --}}
        <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Report by users</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                    <table class="table">
                        <thead class="thead-dark">
                          <tr>
                            <th scope="col">#</th>
                            <th scope="col">Employee</th>
                            <th scope="col">Worked Time</th>
                          </tr>
                        </thead>
                        <tbody id="employeesTableBody">


                        </tbody>
                </table>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
          </div>
    {{-- End of Modal --}}


</div>


@endsection

@section('scripts')
<script src="https://d3js.org/d3.v4.js"></script>
<script src="https://d3js.org/d3-scale-chromatic.v1.min.js"></script>
<script>
    //This function converts seconds to H:i:s format
    function secondsTimeSpanToHMS(s) {
        var h = Math.floor(s/3600); //Get whole hours
        s -= h*3600;
        var m = Math.floor(s/60); //Get remaining minutes
        s -= m*60;
        return h+":"+(m < 10 ? '0'+m : m)+":"+(s < 10 ? '0'+s : s); //zero padding on minutes and seconds
    }

    //This function sends the required data from the fron-end to the back-end
    //It displays the table with project, task, worked time and action
    function calculateTimeByEmployee(){

        var companyId = $('#companyDropdown').val(); //gets the company id from the dropdown
        var to = $('#to').val(); //gets the 'to' date from the picker
        var from = $('#from').val(); //gets the 'from' date from the picker

        $.ajax({
            url: "reports/employeeTime",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type: "POST",
            data: {id: companyId, from: from, to:to},
            cache: false,
            async: false,
            dataType: "json",
            processData: "false"
        })

        .done(function(response){

            //The following lines iterates through the response.companies and response.workingHours collections
            //and displayes the table entries
            var i = 1; //incrementor used for the entry index
                $.each(response.companies, function(key, company){
                    $.each(company.project, function(key, project){
                        $.each(project.tasks, function(key, task){
                            $.each(response.workingHours, function(key, wh){
                                if(wh.task_id == task.id){
                                    console.log(wh.user_id);
                                    $('#tasksTableBody').append('<tr> <th>' + i + '</th>' + '<td>' + project.title + '</td>' + '<td>' + task.title + '</td>' + '<td>' + secondsTimeSpanToHMS(wh.worked) + '</td>' + '<td><button class="btn btn-info user" data-toggle="modal" data-target="#user' + wh.user_id +'" onclick="employeeReport('+ wh.task_id + ',' + wh.user_id +')">Users</button></td>');
                                    $('.modal').attr('id', 'user' + wh.user_id);
                                    i++;
                                }
                            })
                            x = 0;
                        })
                    })
                })

                if(response.workingHours.length == 0 || response.companies.length == 0){
                  alert("Nothing to show. Please make sure that the information is correct and try again. If the information is correct, then there is nothing to show for this company at the specified dates.");
                }
        })

        .fail(function(response){

        });
    }

    //This function is used on the "users" action button to put data into the modal
    //It displays the rows
    function employeeReport(task_id, employee_id){

        var to = $('#to').val();
        var from = $('#from').val();

        $.ajax({
            url: "reports/employeeReport",
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type: "POST",
            data: {id: task_id, employee_id:employee_id, to: to, from:from},
            cache: false,
            async: false,
            dataType: "json",
            processData: "false"
        })

        .done(function(response){

            $("#employeesTableBody").empty();

            var i = 1;

            $.each(response.employees, function(key, employee){
                $.each(response.workingHours, function(key, wh){
                    if(employee.id == wh.user_id){
                        $('#employeesTableBody').append('<tr><th>' + i + '</th>' + '<td>' +  employee.name +'</td>' +'<td>'+  secondsTimeSpanToHMS(wh.worked) +'</td></tr>');
                        i++;
                    }
                })
            })
        })
    }

    //Pie Chart
   // set the dimensions and margins of the graph

var companies = {!! json_encode($companies->toArray(), JSON_HEX_TAG) !!};
// console.log(companies.constructor === Object);

var width = 450
    height = 450
    margin = 60

// The radius of the pieplot is half the width or half the height (smallest one). I subtract a bit of margin.
var radius = Math.min(width, height) / 2 - margin

// append the svg object to the div called 'my_dataviz'
var svg = d3.select("#pieChart")
  .append("svg")
    .attr("width", width)
    .attr("height", height)
  .append("g")
    .attr("transform", "translate(" + width / 2 + "," + height / 2 + ")");

// Create dummy data

var data = {};

companies.forEach(function(company){
  company.project.forEach(function(project){
    project.tasks.forEach(function(task){
      data[company.companyName] = task.worked_time;
    })
  })
})

// set the color scale
var color = d3.scaleOrdinal()
  .domain(["a", "b", "c", "d", "e", "f", "g", "h"])
  .range(d3.schemeDark2);

// Compute the position of each group on the pie:
var pie = d3.pie()
  .sort(null) // Do not sort group by size
  .value(function(d) {return d.value; })
var data_ready = pie(d3.entries(data))

// The arc generator
var arc = d3.arc()
  .innerRadius(radius * 0.5)         // This is the size of the donut hole
  .outerRadius(radius * 0.8)

// Another arc that won't be drawn. Just for labels positioning
var outerArc = d3.arc()
  .innerRadius(radius * 0.9)
  .outerRadius(radius * 0.9)

// Build the pie chart: Basically, each part of the pie is a path that we build using the arc function.
svg
  .selectAll('allSlices')
  .data(data_ready)
  .enter()
  .append('path')
  .attr('d', arc)
  .attr('fill', function(d){ return(color(d.data.key)) })
  .attr("stroke", "white")
  .style("stroke-width", "2px")
  .style("opacity", 0.7)

// Add the polylines between chart and labels:
svg
  .selectAll('allPolylines')
  .data(data_ready)
  .enter()
  .append('polyline')
    .attr("stroke", "black")
    .style("fill", "none")
    .attr("stroke-width", 1)
    .attr('points', function(d) {
      var posA = arc.centroid(d) // line insertion in the slice
      var posB = outerArc.centroid(d) // line break: we use the other arc generator that has been built only for that
      var posC = outerArc.centroid(d); // Label position = almost the same as posB
      var midangle = d.startAngle + (d.endAngle - d.startAngle) / 2 // we need the angle to see if the X position will be at the extreme right or extreme left
      posC[0] = radius * 0.95 * (midangle < Math.PI ? 1 : -1); // multiply by 1 or -1 to put it on the right or on the left
      return [posA, posB, posC]
    })

// Add the polylines between chart and labels:
svg
  .selectAll('allLabels')
  .data(data_ready)
  .enter()
  .append('text')
    .text( function(d) { console.log(d.data.key) ; return d.data.key } )
    .attr('transform', function(d) {
        var pos = outerArc.centroid(d);
        var midangle = d.startAngle + (d.endAngle - d.startAngle) / 2
        pos[0] = radius * 0.99 * (midangle < Math.PI ? 1 : -1);
        return 'translate(' + pos + ')';
    })
    .style('text-anchor', function(d) {
        var midangle = d.startAngle + (d.endAngle - d.startAngle) / 2
        return (midangle < Math.PI ? 'start' : 'end')
    })


</script>
@endsection
