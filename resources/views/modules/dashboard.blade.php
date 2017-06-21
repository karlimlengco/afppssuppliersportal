@section('title')
OverView
@stop

@section('contents')

    <div class="row">
        <div class="twelve columns">
            <canvas id="lineChart" ></canvas>
        </div>
    </div>
@stop

@section('scripts')

<!-- Chart.js -->
<script src="/vendors/Chart.js/dist/Chart.min.js"></script>
<script type="text/javascript">
    // Chart.defaults.global.legend = {
    //     enabled: false
    // };

    const data = values;
    console.log(data);

  // Line chart
    var ctx = document.getElementById("lineChart");
    var lineChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: months,
            datasets: data
        },

        options: {
            responsive: true,
            title:{
                display:true,
                text:"RFQ Unit Graph"
            },
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                        userCallback: function(label, index, labels) {
                            // when the floored value is the same as the value we have a whole number
                            if (Math.floor(label) === label) {
                                return label;
                            }

                        },
                    }
                }],
            }

        }
    });
</script>
@stop