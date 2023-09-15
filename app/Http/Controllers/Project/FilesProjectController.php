<?php

namespace App\Http\Controllers\Project;

use App\Models\Project\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FilesProjectController
{
    public function saveFile(Request $request)
    {
        $projectId = $request->project_id;
        $file = $request->file;
        $fileName = $file->getClientOriginName();

        $url = Storage::disk('public')->putFileAs('project_' . $projectId, $file, $fileName);

        File::on()->create([
            'project_id' => $projectId,
            'url' => $url
        ]);

        return $this->getFileList($projectId);
    }

    public function deleteFile()
    {
    }

    private function getFileList($projectId)
    {
        return response()->json([
            'result' => true,
            'html' => ''
        ]);
    }
}
