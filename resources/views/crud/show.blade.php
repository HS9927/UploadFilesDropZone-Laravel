@extends("crud.templates.master")

{{-- Custom Styles --}}
@push("custom-styles")
    <style>
        .remove_image:hover {
            text-decoration: underline;
        }

        .remove_image:focus {
            box-shadow: none;
            text-decoration: underline;
        }
    </style>
@endpush

{{-- Contents --}}
@section("contents")

    <h2 class="card-title mb-3 text-center" style="font-size: 1.8rem">Information</h2>

    <form action="{{route("store")}}" method="POST" enctype="multipart/form-data"
          id="main-frm">
        @csrf

        <input type="hidden" name="folder_name" class="w-100" value="{{$folder_name}}"/>

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

    <div class="mb-2">
        <form action="{{route("store.file", $folder_name)}}" id="dropzoneForm" class="dropzone mt-4"
              style="height: 100px;">
            @csrf
            <div class="fallback">
                <input name="file" type="file" multiple="multiple">
            </div>
            <div class="dz-message needsclick">
                <div class="mb-3">
                    <i class="display-4 text-muted mdi mdi-upload-network-outline"></i>
                </div>

                <h4>Drop files here or click to upload.</h4>
            </div>
        </form>
    </div>

    <div class="uploaded_att mt-3"></div>

    <div class="text-center mt-2">
        <a href="{{ route("index") }}" class="btn btn-danger px-5 mx-2">
            <img src="{{ asset("images/crud/cancel.png") }}" width="20px" height="20px" /> Cancel</a>
        <button type="button" class="btn btn-primary waves-effect waves-light px-5 mx-2" id="btn-submit">
            <img src="{{ asset("images/crud/save.png") }}" width="20px" height="20px" /> Save
        </button>
    </div>
@endsection

{{-- Custom Scripts --}}
@push("custom-scripts")
    <script type="text/javascript">
        $(document).ready(function () {

        });

        $("#btn-submit").on("click", function () {
            $("#main-frm").find("[type='submit']").trigger("click");
        });


    </script>

    <!-- Script Upload File -->
    <script type="text/javascript">
        Dropzone.options.dropzoneForm = {
            autoProcessQueue: true,
            parallelUploads: 50,
            acceptedFiles: ".png,.jpg,.pdf",

            init: function () {
                myDropzone = this;

                myDropzone.processQueue();

                this.on("complete", function () {
                    if (this.getQueuedFiles().length == 0 && this.getUploadingFiles().length == 0) {
                        console.log(123);
                        var _this = this;
                        _this.removeAllFiles();
                    }
                    load_images();
                });
            }
        };


        load_images();

        function load_images() {
            $('document').ready(function () {
                var folderName = "{{ $folder_name }}";
                $.ajax({
                    url: "{{ route('fetch.file') }}",
                    data: {folder: folderName},
                    success: function (data) {
                        $('.uploaded_att').html(data);
                    }
                })
            });
        }

        $(document).on('click', '.remove_image', function () {
            var name = $(this).attr('id');
            var folderName = "{{ $folder_name }}";
            $.ajax({
                url: "{{ route('destroy.temp.file') }}",
                data: {
                    folder: folderName,
                    name: name
                },
                success: function (data) {
                    load_images();
                }
            })
        });
    </script>
@endpush
