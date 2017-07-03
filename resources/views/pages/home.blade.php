@extends('app')

@section('content')
    <div class="columns is-clearfix">
        <div class="column is-offset-2 is-4">
            <table class="table is-bordered">
                <tbody>
                    <tr>
                        <td>
                            <span class="icon is-small"><i class="fa fa-calendar"></i></span>
                            <span>Days since Kickstarter ended: 1683</span>
                         </td>
                    </tr>
                    <tr class="task-released">
                        <td>
                            <span class="icon is-small"><i class="fa fa-battery-4"></i></span>
                            <span>Released: 42%</span>
                            <span class="icon is-pulled-right"><i class="fa fa-question-circle-o"></i></span>
                         </td>
                    </tr>
                    <tr class="task-partially-released">
                        <td>
                            <span class="icon is-small"><i class="fa fa-battery-3"></i></span>
                            <span>Partially Released: 42%</span>
                            <span class="icon is-pulled-right"><i class="fa fa-question-circle-o"></i></span>
                         </td>
                    </tr>
                    <tr class="task-in-progress">
                        <td>
                            <span class="icon is-small"><i class="fa fa-battery-2"></i></span>
                            <span>In-Progress: 42%</span>
                            <span class="icon is-pulled-right"><i class="fa fa-question-circle-o"></i></span>
                        </td>
                    </tr>
                    <tr class="task-stagnant">
                        <td>
                            <span class="icon is-small"><i class="fa fa-battery-1"></i></span>
                            <span>Stagnant: 42%</span>
                            <span class="icon is-pulled-right"><i class="fa fa-question-circle-o"></i></span>
                        </td>
                    </tr>
                    <tr class="task-broken">
                        <td>
                            <span class="icon is-small"><i class="fa fa-chain-broken"></i></span>
                            <span>Broken: 42%</span>
                            <span class="icon is-pulled-right"><i class="fa fa-question-circle-o"></i></span>
                        </td>
                    </tr>
                    <tr class="task-cut">
                        <td>
                            <span class="icon is-small"><i class="fa fa-scissors"></i></span>
                            <span>Cut: 42%</span>
                            <span class="icon is-pulled-right"><i class="fa fa-question-circle-o"></i></span>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div id="chart"></div>
        </div>
        <div class="column is-4">
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Eos odit quod, hic unde eius similique impedit alias eaque veritatis vel ratione, eligendi perferendis architecto possimus, ullam nam consequuntur quasi totam enim eum!</p>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Iste corrupti repudiandae tenetur cupiditate dolor quaerat quibusdam tempore, voluptas adipisci eos sapiente quidem expedita quas, porro laborum doloribus aliquam, qui obcaecati. Necessitatibus perferendis blanditiis enim itaque architecto impedit beatae quis magnam dignissimos minima ipsam dolores, culpa.</p>
            <p><b>Accuracy Confidence:</b> <a class="button is-small">95.7%</a></p>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Mollitia commodi quam numquam nulla, saepe autem id quibusdam unde blanditiis voluptatibus maiores delectus deserunt, minima, harum, aliquid vel perferendis quia eum ab sequi qui? Aliquam velit suscipit, maiores consectetur odio perferendis optio sint blanditiis, earum est nam tenetur ratione enim. Praesentium magnam ipsum architecto quod tempora quis et esse, eum. Harum soluta optio officia delectus quas dicta, amet eveniet velit sit voluptatum.</p>
            <div>
                <b>Estimated Total Progress on Major Features:</b>
                <progress class="progress" value="15" max="100">15%</progress>
            </div>
        </div>
    </div>
    <div class="columns">

        <div class="column is-offset-2 is-8">
            <div id="app" class="container">
                <example-item></example-item>
            </div>

            {{--<table class="table is-striped">
                <thead>
                    <tr>
                        <th><abbr title="Name">Name</abbr></th>
                        <th><abbr title="Description">Description</abbr></th>
                        <th><abbr title="Status">Status</abbr></th>
                    </tr>
                </thead>

                <tbody>


                    @for ($i = 0; $i < 25; $i++)
                    <tr>
                        <td>Task #{{ $i }}</td>
                        <td>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quo, numquam.</td>

                        @if ($i % 2 == 0)
                        <td><i class="fa fa-check"></i></td>
                        @else
                        <td><i class="fa fa-warning"></i></td>
                        @endif
                    </tr>
                    @endfor


                </tbody>

            </table>--}}

        </div>

    </div>



    <script type="text/javascript">
    Highcharts.chart('chart', {
        legend: {
            enabled: false,
            verticalAlign: 'bottom',
        },
        plotOptions: {
            series: {
                pointStart: 2012
            }
        },
        series: [{
            name: 'Installation',
            data: [43934, 52503, 57177, 69658, 97031, 119931, 137133, 154175]
        }, {
            name: 'Manufacturing',
            data: [24916, 24064, 29742, 29851, 32490, 30282, 38121, 40434]
        }, {
            name: 'Sales & Distribution',
            data: [11744, 17722, 16005, 19771, 20185, 24377, 32147, 39387]
        }, {
            name: 'Project Development',
            data: [null, null, 7988, 12169, 15112, 22452, 34400, 34227]
        }, {
            name: 'Other',
            data: [12908, 5948, 8105, 11248, 8989, 11816, 18274, 18111]
        }]
    });
    </script>
@endsection
