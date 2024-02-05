<?php

namespace App\Http\Controllers\Project;

use App\Models\Project\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FilesProjectController
{
    public function saveFile(Request $request)
    {
        $projectId = $request->project_id ?? null;
        $clientId = $request->client_id ?? null;
        $file = $request->file;
        $comment = $request->comment;
        $fileName = $file->getClientOriginalName();

        switch (true) {
            case !is_null($projectId):

                $type = 'project_id';
                $id = $projectId;
                break;

            case !is_null($clientId):
                $type = 'client_id';
                $id = $clientId;
                break;

            default :
                return response()->json([
                    'result'  => false,
                    'message' => "Ошибка загрузки файла"
                ]);
        }

        $has = (boolean)File::on()->where($type, $id)->where('file_name', $fileName)->count();

        if ($has) {
            return response()->json([
                'result'  => false,
                'message' => "Файл с таким названием уже существует"
            ]);
        }

        $dirName = $type == "project_id" ? 'project_' : 'client_';
        $url = Storage::disk('public')->putFileAs($dirName . $id, $file, $fileName);

        File::on()->create([
            $type       => $id,
            'url'       => $url,
            'file_name' => $fileName,
            'comment'   => $comment
        ]);

        return $this->getFileList($type, $id);
    }

    public function deleteFile(Request $request, $id)
    {
        $file = File::on()->find($id);
        Storage::disk('public')->delete($file['url']);
        $column = !is_null($file->project_id) ? 'project_id' : 'client_id';
        $id = $request->project_id ?? $request->client_id;
        $file->delete();

        return $this->getFileList($column, $id);
    }

    private function getFileList($column, $id)
    {
        $files = File::on()->where($column, $id)->get()->toArray();

        return response()->json([
            'result' => true,
            'html'   => view('Render.Project.file_list', ['column' => $column, 'files' => $files, 'id' => $id])->render()
        ]);
    }
}
