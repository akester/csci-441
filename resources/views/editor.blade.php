<x-app-layout>
    <!--Righ below header in the center, it holds the control buttons-->
    <div id="control">
        <div id="insideControl">
            <div id="control-1">
                <button class="button-12" role="button">
                    <a href="/upload">Upload pdf</a>
                </button>
                <button class="button-12" role="button">
                    <a target="_blank" href="/download/{{ $metadata->document_id }}">Download pdf</a>
                </button>
                <button class="button-12" role="button">Upload TOC</button>
                <button class="button-12" role="button">Download TOC</button>
            </div>
            <div id="control-2">
                <button class="button-12" role="button">Extract TOC</button>
                <button class="button-12" role="button">Insert TOC</button>
                <button class="button-12" role="button">Save</button>
                <button class="button-12" role="button">Help</button>
            </div>>
        </div>
    </div>

    <!--Right below header, right side - holds login/register buttons-->
    <div id="login"></div>
    
    <div id="content">
        <div id="app">
            <editor></editor>
        </div>
    </div>

    <script type="text/javascript">
        var metadata = {!! json_encode($metadata) !!};
    </script>
</x-app-layout>
