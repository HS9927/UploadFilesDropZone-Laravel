@extends("crud.templates.master")

{{-- Contents --}}
@section("contents")

    <h2 class="card-title mb-3 text-center" style="font-size: 1.8rem">Information</h2>

    <form action="{{route("store")}}" method="POST" enctype="multipart/form-data"
          id="main-frm">
        @csrf

        <div class="form-group">
            <label>Name</label>
            <input type="text" name="name" class="form-control" required />
        </div>

        <div class="form-group mt-2">
            <label>Subject</label>
            <input type="text" name="subject" class="form-control" required/>
        </div>

        <button type="submit" style="display: none;"></button>

    </form>

    <div class="uploaded_att mt-3"></div>

    <div class="text-center mt-2">
        <a href="{{ route("index") }}" class="btn btn-danger px-5 mx-2">
            <img src="{{ asset("images/crud/cancel.png") }}" width="20px" height="20px" /> Cancel
        </a>
    </div>
@endsection

{{-- Custom Scripts --}}
@push("custom-scripts")
    <script type="text/javascript">

        /// TODO: Load All Files from Permanent Location
        load_images();

        function load_images() {
            $('document').ready(function () {
                var folderName = "{{ $data->folder_name }}";
                $.ajax({
                    url: "{{ route('fetch.permanent.file') }}",
                    data: {folder: folderName},
                    success: function (data) {
                        $('.uploaded_att').html(data);
                    }
                })
            });
        }

        $("#btn-submit").on("click", function () {
            $("#main-frm").find("[type='submit']").trigger("click");
        });




    </script>

@endpush
