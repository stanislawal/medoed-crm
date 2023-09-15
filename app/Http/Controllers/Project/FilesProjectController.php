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
        $fileName = $file->getClientOriginalName();

        $has = (boolean)File::on()->where('project_id', $projectId)->where('file_name', $fileName)->count();

        if ($has) {
            return response()->json([
                'result' => false,
                'message' => "Файл с таким названием уже существует"
            ]);
        }

        $url = Storage::disk('public')->putFileAs('project_' . $projectId, $file, $fileName);

        File::on()->create([
            'project_id' => $projectId,
            'url' => $url,
            'file_name' => $fileName
        ]);

        return $this->getFileList($projectId);
    }

    public function deleteFile(Request $request, $id)
    {
        $file = File::on()->find($id);
        Storage::disk('public')->delete($file['url']);
        $file->delete();
        return $this->getFileList($request->project_id);
    }

    private function getFileList($projectId)
    {
        $files = File::on()->where('project_id', $projectId)->get()->toArray();

        return response()->json([
            'result' => true,
            'html' => view('Render.Project.file_list', ['files' => $files, 'projectId' => $projectId])->render()
        ]);
    }
}
