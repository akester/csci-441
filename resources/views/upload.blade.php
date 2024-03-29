<x-app-layout>
    <!--Righ below header in the center, it holds the control buttons-->
    <div id="control">
        <div id="insideControl"></div>
    </div>

    <!--Right below header, right side - holds login/register buttons-->
    <div id="login"></div>

    <div id="content">
        Please select a PDF document to upload.

        <form method="POST" enctype="multipart/form-data">
            @csrf

            <label for="file" style="display: none;">Select File</label>
            <input id="file"
                type="file"
                name="file"
                accept=".pdf"
                class="@error('file') is-invalid @enderror">
 
            @error('file')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror

            <button class="button-block" type="submit")>Submit</button>
        </form>
    </div>
</x-app-layout>
