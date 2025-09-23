@extends('layouts.app')
@section('title', __('lang_v1.gallery_setting'))
<style>
    .image-preview {
        width: 100%;
        height: 300px;
        background-image: url("/uploads/business_header/slider_1.jpg");
        background-size: cover;
        background-repeat: no-repeat;
        background-attachment: fixed;
        background-position: center;
    }
</style>
@section('content')

    <div class="container">
        <h3>@lang('lang_v1.gallery_setting')</h3>

       <form action="{{ action('ProductGallery@update') }}" method="POST" id="product_setting" enctype="multipart/form-data">


        <div class="row">

   <div class="col-sm-4">
    <div class="form-group">
        <br>
        <label>
            <input type="checkbox" name="enable_stock" value="1" class="input-icheck" id="enable_stock" checked>
            <strong>هل تريد تفعيل المتجر من خارج الموقع؟</strong>
        </label>
    </div>
</div>

<div class="col-sm-4">
    <div class="form-group">
        <label for="slug">مسار المعرض</label>
        <input type="text" name="slug" id="slug" class="form-control" required placeholder="slug" style="text-align: left;">
        @error('slug')
            <small class="text-danger">{{ $errors->first('slug') }}</small>
        @enderror
    </div>
</div>


        </div>
        <div class="row">

       <div class="col-md-4">
    <div class="form-group">
        <label for="file">اللوجو</label>
        <input type="file" name="image" id="file" accept="image/*">
    </div>
</div>

<div class="col-md-12">
    <div class="">
        <div id="imagePreview" class="images-container">
            <img class="img-responsive" src="" style="width: 100%;" />
        </div>
    </div>
</div>


        </div>

        <br><br>
        <button type="submit" class="btn btn-primary">Save</button>

        </form>
    </div>


@endsection

@section('javascript')
    <script src="{{ url('js/product.js?v=' . $asset_v) }}"></script>
    <script src="{{ url('js/opening_stock.js?v=' . $asset_v) }}"></script>
    {{--  <script src="{{ url('js/report.js?v=' . $asset_v) }}"></script> --}}
    <script type="text/javascript">
        $(document).on('keypress', '#slug', function(event) {
            var key = event.keyCode;
            if (key === 32) {
                event.preventDefault();
            }
        });

        $(document).on('keyup', '#slug', function(event) {

        });



        var img_setting = {
            showUpload: false,
            showPreview: false,
            browseLabel: LANG.file_browse_label,
            removeLabel: LANG.remove,
            previewSettings: {
                image: {
                    width: '100%',
                    height: '300px',
                    'max-width': '100%',
                    'max-height': '100%'
                },
            },
        };

        $('#file').fileinput(img_setting);



        function handleFileSelect(evt) {
            var files = evt.target.files; // FileList object

            // Loop through the FileList and render image files as thumbnails.
            for (var i = 0, f; f = files[i]; i++) {

                // Only process image files.
                if (!f.type.match('image.*')) {
                    continue;
                }

                var reader = new FileReader();

                // Closure to capture the file information.
                reader.onload = (function(theFile) {
                    return function(e) {
                        // Render thumbnail.
                        var span = document.createElement('span');
                        span.innerHTML = ['<img class="thumb" style="width:20px;hieght:20px;" src="', e.target
                            .result,
                            '" title="', escape(theFile.name), '"/>'
                        ].join('');
                        document.getElementById('list').insertBefore(span, null);
                    };
                })(f);

                // Read in the image file as a data URL.
                reader.readAsDataURL(f);
            }
        }

        document.getElementById('file').addEventListener('change', fileValidation, false);

        function fileValidation() {
            var fileInput = document.getElementById('file');
            var filePath = fileInput.value;
            var allowedExtensions = /(\.jpg|\.jpeg|\.png|\.gif)$/i;
            if (!allowedExtensions.exec(filePath)) {
                notifier.alert('عفوا برجاء إختيار صورة');
                fileInput.value = '';
                return false;
            } else {
                //Image preview
                if (fileInput.files && fileInput.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        document.getElementById('imagePreview').innerHTML = '<img  class="img-responsive" src="' + e
                            .target.result + '" style="width: 100%" />';
                    };
                    reader.readAsDataURL(fileInput.files[0]);
                }
            }
        }
    </script>

@endsection
