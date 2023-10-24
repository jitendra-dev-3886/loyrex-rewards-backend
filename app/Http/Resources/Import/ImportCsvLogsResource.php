<?php

namespace App\Http\Resources\Import;

use Illuminate\Http\Resources\Json\JsonResource;

class ImportCsvLogsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'filename' => (string)$this->filename,
            'file_path' =>(string) $this->file_path,
            'model_name' => $this->model_name,
            'user_id' => $this->user_id,
            'user' => $this->user,
            'status' => $this->status,
            'status_text' => config('constants.import_csv_log.status.'.$this->status),
            'no_of_rows' => (string) $this->no_of_rows,
            'error_log' => json_decode($this->error_log),
            'created_at' => (string)$this->created_at,
            'updated_at' => (string)$this->updated_at,
            'deleted_at' => (string)$this->deleted_at
        ];
    }
}
