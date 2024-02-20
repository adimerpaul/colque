<div class="table-responsive">
    <table class="table table-striped" id="minerals-table">
        <thead>
        <tr>
            <th>#</th>
            <th>Símbolo</th>
            <th>Nombre</th>
            <th>Unidad cot. diaria</th>
            <th>Unidad cot. oficial</th>
{{--            <th></th>--}}
        </tr>
        </thead>
        <tbody>
        @foreach($minerales as $mineral)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $mineral->simbolo }}</td>
                <td>{{ $mineral->nombre }}</td>
                <td>{{ $mineral->diaria }}</td>
                <td>{{ $mineral->oficial }}</td>
{{--                <td style="width: 250px">--}}
{{--                    <div class='btn-group'>--}}

{{--                        <a href="#" class='btn btn-warning btn-xs'><i--}}
{{--                                class="glyphicon glyphicon-list"></i> Leyes</a>--}}

{{--                    </div>--}}
{{--                </td>--}}
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
