@extends('layouts.layout')

@section('title')
Dashboard
@endsection

@section('links')
    <link rel="stylesheet" type="text/css" href="/css/dashboard.css">
    <script src="https://d3js.org/d3.v4.js"></script>
@endsection

@section('content')

<div class="container">
    <div class="container-fluid barPlotContainer mt-5">
        <div id="barPlot"></div>
    </div>


        <div class="row justify-content-center mt-5">

            <div class="card-container col-md-4 col-sm-12 col-xs-12">
                <div class="card-deck mb-3 text-center">
                    <div class="card mb-4 shadow-sm">
                        <div class="card-header">
                            <h4 class="my-0 font-weight-normal bla" id="bla">Companies</h4>
                        </div>

                        <div class="card-body">
                            <h1 class="card-title pricing-card-title" id="nrCompanies">{{ $nrCompanies }}</h1>
                            <hr>
                            <a class="btn btn-dark" href="/companies">View</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-container col-md-4 col-sm-12 col-xs-12">
                <div class="card-deck mb-3 text-center">
                    <div class="card mb-4 shadow-sm">
                        <div class="card-header">
                            <h4 class="my-0 font-weight-normal">Projects</h4>
                        </div>

                        <div class="card-body">
                            <h1 class="card-title pricing-card-title" id ="nrProjects">{{ $nrProjects }}</h1>
                            <hr>
                            <a class="btn btn-dark" href="/companies">View</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-container col-md-4 col-sm-12 col-xs-12">
                <div class="card-deck mb-3 text-center">
                    <div class="card mb-4 shadow-sm">
                        <div class="card-header">
                            <h4 class="my-0 font-weight-normal">Tasks</h4>
                        </div>

                        <div class="card-body">
                            <h1 class="card-title pricing-card-title" id="nrTasks">{{ $nrTasks }}</h1>
                            <hr>
                            <a class="btn btn-dark" href="/companies">View</a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
</div>
@endsection

@section('scripts')
<script>

    let nrCompanies = document.querySelector("#nrCompanies").textContent;
    let nrProjects = document.querySelector("#nrProjects").textContent;
    let nrTasks = document.querySelector("#nrTasks").textContent;

    var data = [
        {group: 'Companies', value: nrCompanies},
        {group: 'Projects', value: nrProjects},
        {group: 'Tasks', value: nrTasks} 
    ];

// set the dimensions and margins of the graph
var margin = {top: 30, right: 30, bottom: 30, left: 30},
    width = 560 - margin.left - margin.right,
    height = 500 - margin.top - margin.bottom;

// append the svg object to the body of the page
var svg = d3.select("#barPlot")
  .append("svg")
    .attr("width", width + margin.left + margin.right)
    .attr("height", height + margin.top + margin.bottom)
  .append("g")
    .attr("transform",
          "translate(" + margin.left + "," + margin.top + ")");

// Initialize the X axis
var x = d3.scaleBand()
  .range([ 0, width ])
  .padding(0.2);
var xAxis = svg.append("g")
  .attr("transform", "translate(0," + height + ")")

// Initialize the Y axis
var y = d3.scaleLinear()
  .range([ height, 0]);
var yAxis = svg.append("g")
  .attr("class", "myYaxis")

  // Update the X axis
  x.domain(data.map(function(d) { return d.group; }))
  xAxis.call(d3.axisBottom(x))

  // Update the Y axis
  y.domain([0, d3.max(data, function(d) { return d.value }) ]);
  yAxis.transition().duration(1000).call(d3.axisLeft(y));

  // Create the u variable
  var u = svg.selectAll("rect")
    .data(data)

  u
    .enter()
    .append("rect") // Add a new rect for each new elements
    .merge(u) // get the already existing elements as well
    .transition() // and apply changes to all of them
    .duration(1000)
      .attr("x", function(d) { return x(d.group); })
      .attr("y", function(d) { return y(d.value); })
      .attr("width", x.bandwidth())
      .attr("height", function(d) { return height - y(d.value); })
      .attr("fill", "#0069D9")

</script>
@endsection