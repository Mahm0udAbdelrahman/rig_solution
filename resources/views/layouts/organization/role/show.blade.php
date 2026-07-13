@extends('layouts.app')

@include('layouts.styles.forms')

<style>
    .switchery.switchery-default{ opacity: 1 !important; }
    .icheckbox_square-green.checked.disabled{ background-position: -48px 0 !important; }
</style>

@section('content')
    <section class="users-edit">
        <div class="card">
            <div class="card-content">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-sm-6">
                            <h5 class="mb-1"><i class="ft-user mr-25"></i>Role Info</h5>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <div class="controls">
                                    <label>Name</label>
                                    <input type="text" name="uname" class="form-control" placeholder="Role Name" value="{{$role->name}}" disabled>
                                    <div class="help-block"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
            $folders = ['app-flow' => ['WorkFlow', 'Persons', 'Organization', 'GeneralInfo'], 'inspection' => ['Lifting', 'Ndt', 'Tubular', 'DropObject', 'Calibration']];
            foreach ($folders as $key => $value)
            {
                echo '<h5 class="ml-1 mb-2">'.ucwords(str_replace('-',' ',$key)).'</h5>';
                foreach ($value as $key1 => $value1)
                {
                    echo '
                    <div class="card">
                        <div class="card-header">
                            <h5 class="bg-info text-white p-1 text-bold-700" style="border-radius: .45rem">'.ucwords(str_replace('-',' ',$value1)).'</h5>
                            <a class="heading-elements-toggle"><i class="la la-ellipsis-h font-medium-3"></i></a>
                            <div class="heading-elements">
                                <ul class="list-inline mb-0 white pt-1">
                                    <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                                    <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                                    <li><a data-action="close"><i class="ft-x"></i></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-content collapse show">
                            <div class="card-body ml-2 mr-2">
                                <div class="row">
                                    <table class="table form-group mb-0">
                                        <thead>
                                            <tr class="border-solid">
                                                <th></th>
                                                ';
                                                if ($key == 'inspection' || $value1 == 'WorkFlow')
                                                {
                                                    echo '<th>Approve</th>';
                                                }
                                                else
                                                {
                                                    echo '<th></th>';
                                                }
                                                echo'
                                                <th>All</th>
                                                <th>Show</th>
                                                <th>Create</th>
                                                <th>Edit</th>
                                                <th>Remove</th>
                                            </tr>
                                        </thead>
                                        <tbody class="controls">';
                                            App\Http\Controllers\CustomController::get_classes(ucfirst($key), ucwords(str_replace('-',' ',$value1)),$role->roles, 's');
                                        echo '</tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>';
                }
            }
        ?>
    </section>
@endsection

@extends('layouts.scripts.forms')
