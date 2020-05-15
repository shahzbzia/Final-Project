<form id="formVid" class="px-6 pt-6" method="POST" enctype="multipart/form-data" action="{{ route('uploadVideo') }}">

    @csrf

    <div class="mb-4 w-full">

        <label class="block text-gray-700 text-sm font-bold mb-2" for="mainVideo">
            Video
        </label>

        <div id="selectVid" class="border border-gray-400 lg:border-l-0 lg:border-t lg:border-gray-400 bg-white rounded-lg shadow-md mb-2 p-10">
            <a href="#">
                
                <svg class="align-middle mx-auto" xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 24 24"><path d="M16 16c0 1.104-.896 2-2 2h-12c-1.104 0-2-.896-2-2v-8c0-1.104.896-2 2-2h12c1.104 0 2 .896 2 2v8zm8-10l-6 4.223v3.554l6 4.223v-12z"/></svg>

            </a>
        </div>

        <div class="progress shadow-md">
            <div class="progress-bar" role="progressbar" aria-valuenow=""
            aria-valuemin="0" aria-valuemax="100" style="width: 0%">
            0%
            </div>
        </div>

        <div id="success">
            
        </div>

        <input id="mainVideo" type="file" class="hidden shadow-md appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('mainVideo') bg-red-200 @enderror" name="mainVideo" value="{{ old('mainVideo') }}" autofocus>

        @error('mainVideo')
            <span class="text-red-500 text-xs italic" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror

    </div>

    <div class="mb-2 flex justify-center">

        <button id="uploadVid" class="hover:bg-{{ Auth::user()->theme->value }}-700 bg-{{ Auth::user()->theme->value }}-500 text-white font-bold py-1 px-2 text-xs rounded focus:outline-none focus:shadow-outline" type="submit">
            Upload
        </button>

    </div>

</form>

<form id="formVidInfo" class="px-6 pt-2 pb-8 mb-4" method="POST" enctype="multipart/form-data" action="{{ route('video.createPost') }}">

    @csrf

    <input id="postId" type="hidden" value="" name="id">

    <div class="mb-4">

        <label class="block text-gray-700 text-sm font-bold mb-2" for="title">
            Title
        </label>

        <input id="titleVid" type="text" class="title shadow-md appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('title') bg-red-200 @enderror" name="title" value="{{ old('title') }}" required autocomplete="email" autofocus>

        <div class="flex flex-col md:flex-row justify-between">
            <div class="flex">
                <svg class="mt-1 ml-1 mr-2" xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24"><path d="M6.188 8.719c.439-.439.926-.801 1.444-1.087 2.887-1.591 6.589-.745 8.445 2.069l-2.246 2.245c-.644-1.469-2.243-2.305-3.834-1.949-.599.134-1.168.433-1.633.898l-4.304 4.306c-1.307 1.307-1.307 3.433 0 4.74 1.307 1.307 3.433 1.307 4.74 0l1.327-1.327c1.207.479 2.501.67 3.779.575l-2.929 2.929c-2.511 2.511-6.582 2.511-9.093 0s-2.511-6.582 0-9.093l4.304-4.306zm6.836-6.836l-2.929 2.929c1.277-.096 2.572.096 3.779.574l1.326-1.326c1.307-1.307 3.433-1.307 4.74 0 1.307 1.307 1.307 3.433 0 4.74l-4.305 4.305c-1.311 1.311-3.44 1.3-4.74 0-.303-.303-.564-.68-.727-1.051l-2.246 2.245c.236.358.481.667.796.982.812.812 1.846 1.417 3.036 1.704 1.542.371 3.194.166 4.613-.617.518-.286 1.005-.648 1.444-1.087l4.304-4.305c2.512-2.511 2.512-6.582.001-9.093-2.511-2.51-6.581-2.51-9.092 0z"/></svg>

                <div id="slugTextVid">https://www.artillary.net/<span id="slugLinkVid"></span></div>
            </div>

            <button type="button" class="text-{{ Auth::user()->theme->value }}-500" onclick="copyToClipboard('#slugTextVid', '#slugLinkVid')">Copy</button>
        </div>

        @error('title')
            <span class="text-red-500 text-xs italic" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror

    </div>

    <div class="mb-4 hidden">

        <label class="block text-gray-700 text-sm font-bold mb-2" for="slug">
            Slug
        </label>

        <input id="slugVid" type="text" class="shadow-md appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('slug') bg-red-200 @enderror" name="slug" value="{{ old('slug') }}" required autocomplete="slug" readonly autofocus>

        @error('slug')
            <span class="text-red-500 text-xs italic" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror

    </div>

    <div class="mb-4">

        <label class="block text-gray-700 text-sm font-semibold mb-2" for="description">

            Description

        </label>

        <textarea name="description" rows="2" cols="50" id="descriptionVid" class="shadow-md appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('description') bg-red-200 @enderror" >{{ old('description') }} </textarea>

        @error('description')
            <span class="text-red-500 text-xs italic" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror

    </div>

    <div class="mb-4 w-full ">

        <label class="block text-gray-700 text-sm font-bold mb-2" for="">
            Market Place
        </label>
        
        <input type="checkbox" id="sellable_vid" name="sellable">
        <label for="sellable_vid"> Would you like this post to be available on the market place?</label><br>

    </div>

    <div id="sellable_details_vid">
        
        <div class="mb-4">

            <label class="block text-gray-700 text-sm font-bold mb-2" for="royaltyFee">
                Royalty Fee
            </label>

            <input id="royaltyFeeVid" type="number" class="shadow-md appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('royaltyFee') bg-red-200 @enderror" name="royaltyFee" value="{{ old('royaltyFee') }}" required autocomplete="royaltyFee">

            @error('royaltyFee')
                <span class="text-red-500 text-xs italic" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror

        </div>

        <div class="mb-4">

            <label class="block text-gray-700 text-sm font-bold mb-2" for="price">
                Price
            </label>

            <input id="priceVid" type="number" class="shadow-md appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('price') bg-red-200 @enderror" name="price" value="{{ old('price') }}" required autocomplete="price">

            @error('price')
                <span class="text-red-500 text-xs italic" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror

        </div>

        <div class="mb-4 w-full">

            <div class="flex">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="dContent">
                    Downloadable Content
                </label>

                <span data-toggle="tooltip" title="This is the place where you can put the downloadable content for the paying customers."><span class="bg-black rounded-full py-0 px-2 ml-2 text-white">?</span></span>
            </div>

            <input id="dContentVid" type="file" class="shadow-md appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('dContent') bg-red-200 @enderror" name="dContent" value="{{ old('dContent') }}" autofocus>

            @error('dContent')
                <span class="text-red-500 text-xs italic" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror

        </div>


    </div>
    

    <div class="flex items-center justify-between">

        <button id="submitVidInfoForm" class="hover:bg-gray-700 bg-black hover:text-gray-900 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
            Post
        </button>

    </div>

</form>

<script>
    $(document).ready(function(){
        //tootltip bootstrap jquery
        $('[data-toggle="tooltip"]').tooltip(); 

        //hide/show sellable details images
        $("#sellable_details_vid").hide();
        $("#sellable_details_vid :input").attr("disabled", true);

        $('#sellable_vid').change(function() {
            if(this.checked) {
                $("#sellable_details_vid").show();
                $("#sellable_details_vid :input").attr("disabled", false);
            }else{
                $("#sellable_details_vid").hide();
                $("#sellable_details_vid :input").attr("disabled", true);
            }
        });
    });
</script>

<script>
    //slug generator image
    $('#titleVid').change(function(e) {
        $.get('{{ route('posts.checkSlug') }}',
            {'title': $(this).val() },
            function(data){
                $('#slugVid').val(data.slug);
                $('#slugLinkVid').html(data.slug);
            }
        );
    });
</script>

<script>
    // Copy to clipboard
    function copyToClipboard(element1, element2) {
      var $temp = $("<input>");
      $("body").append($temp);
      $temp.val($(element1).text()).select();
      document.execCommand("copy");
      $temp.remove();
    }

</script>

<script>
    //select image
    $('#selectVid').click(function() {
        $("#mainVideo").click();
    });
</script>

<script>
    //upload video
    $("#formVidInfo").hide();
    $("#formVidInfo :input").attr("disabled", true);

    $('#formVid').ajaxForm({
          beforeSend:function(){
            $('#success').empty();
          },
          uploadProgress:function(event, position, total, percentComplete)
          {
            $('.progress-bar').text(percentComplete + '%');
            $('.progress-bar').css('width', percentComplete + '%');
          },
          success:function(data)
          {
            if(data.errors)
            {
              $('.progress-bar').text('0%');
              $('.progress-bar').css('width', '0%');
              $('#success').html('<span class="text-danger"><b>'+data.errors+'</b></span>');
            }
            if(data.success)
            {
              $('.progress-bar').text('Uploaded');
              $('.progress-bar').css('width', '100%');
              $('#success').html('<span class="text-success"><b>'+data.success+'</b></span><br /><br />');
              $('#success').append(data.video);
              $('#postId').val(data.postId);

              $("#formVidInfo").show();
              $("#formVidInfo :input").attr("disabled", false);
              $("#sellable_details_vid :input").attr("disabled", true);

              $("#uploadVid").toggleClass("hidden");
            }
          }
        });

</script>