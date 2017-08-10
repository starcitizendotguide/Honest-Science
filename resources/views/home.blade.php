@extends('layouts.app')

@section('content')

    <div class="columns is-clearfix m-t-10">
        <div class="column is-offset-2 is-4">
            <table class="table is-bordered">
                <tbody>
                    <tr>
                        <td>
                            <span class="icon is-small"><i class="fa fa-calendar"></i></span>
                            <span>CIG's first officed opened {{ $list['first-office'] }}</span>
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
                            <span>Cut/Broken: 42%</span>
                            <span class="icon is-pulled-right"><i class="fa fa-question-circle-o"></i></span>
                        </td>
                    </tr>
                </tbody>
            </table>
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
    <div>
        <task-list-item></task-list-item>
    </div>

@endsection
