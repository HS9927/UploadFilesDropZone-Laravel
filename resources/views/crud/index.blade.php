@extends("crud.templates.master")

@section("contents")
    <div class="container">
        <h1 class="my-3 text-center">Upload Files using DropZone</h1>
        <div class="d-flex justify-content-center">
            <a href="{{ route("create") }}" class="btn btn-primary w-100 mb-3">
                <img src="{{ asset("images/crud/add.png") }}" alt="add-icon" width="20px" height="20px"/>
                &nbsp; New
            </a>
        </div>
        <table id="mainDataTable" class="display">
            <thead>
            <tr>
                <th>No</th>
                <th>Name</th>
                <th>Subject</th>
                <th>Folder Name</th>
            </tr>
            </thead>
            <tbody>
                @foreach($datas as $data)
                    <tr>
                        <td>{{ $loop->index + 1 }}</td>
                        <td>{{ $data->name }}</td>
                        <td>{{ $data->subject }}</td>
                        <td>{{ $data->folder_name }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

@endsection




