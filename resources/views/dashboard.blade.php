<x-app-layout>
    <!--Righ below header in the center, it holds the control buttons-->
    <div id="control">
        <div id="insideControl">
                <div id="control-1">
                    <button class="button-12" role="button">
                        <a href="/upload">Upload pdf</a>
                    </button>
                </div>
        </div>
    </div>

    <!--Right below header, right side - holds login/register buttons-->
    <div id="login"></div>
    
    <div id="content">
    <table id="editor-table">
        @foreach ($documents as $doc)
            <tr>
                <td><a href="/editor/{{$doc->id}}">{{ $doc->filename }}</a></td>
            </tr>
        @endforeach
    </table>
    </div>
</x-app-layout>
