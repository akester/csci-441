<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveMetadata;
use App\Http\Requests\UploadDocumentRequest;
use App\Models\Bookmark;
use App\Models\Document;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Process\Exception\ProcessFailedException;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function UploadFile()
    {
        return view('upload');
    }

    public function Editor($id)
    {
        $metadata = Document::findOrFail($id)->metadata;

        $metadata->LoadBookmarks();

        return view('editor', [
            'metadata' => $metadata
        ]);
    }

    public function EditorSave($id, SaveMetadata $request)
    {
        $metadata = Document::findOrFail($id)->metadata;

        DB::table('bookmarks')->where('metadata_id', $metadata->id)->delete();
        $metadata->bookmarks = $request->bookmarks;
        $metadata->SaveBookmarksArray();

        return new JsonResponse(['message' => 'save ok']);
    }

    public function UploadFilePost(UploadDocumentRequest $request)
    {
        $file = $request->file('file');

        $document = new Document();
        $document->owner_id = auth()->user()->id;
        $document->Upload($file);

        return redirect('/dashboard');
    }

    public function GetMetadata($id, Request $request)
    {
        $document = Document::findOrFail($id);

        try {
            $document->GetMetadata();
        } catch (ProcessFailedException $e) {
            return new JsonResponse([
                'error' => 'unable to get metadata',
                'exception' => $e->getMessage()
            ]);
        }
    }
}
