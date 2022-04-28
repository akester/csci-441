<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Symfony\Component\Process\Exception\ProcessFailedException;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function UploadFile(Request $request) {
        $file = $request->file('test');

        $document = new Document();

        $document->owner_id = auth()->user()->id;

        $document->Upload($file);

        var_dump($document);
    }

    public function GetMetadata($id, Request $request) {
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
