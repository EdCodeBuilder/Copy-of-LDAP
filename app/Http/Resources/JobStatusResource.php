<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class JobStatusResource extends JsonResource
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
            'id' => $this->id ?? null,
            'key' => $this->key ?? null,
            'job_id' => $this->job_id ?? null,
            'type' => $this->type ?? null,
            'queue' => $this->queue ?? null,
            'attempts' => $this->attempts ?? null,
            'progress_now' => $this->progress_now ?? null,
            'progress_max' => $this->progress_max ?? null,
            'status' => $this->status ?? null,
            'input' => $this->input ?? null,
            'output' => $this->output ?? null,
            'user_id' => (int) $this->user_id ?? null,
            'created_at' => isset($this->created_at) ? $this->created_at->format('Y-m-d H:i:s') : null,
            'updated_at' => isset($this->updated_at) ? $this->updated_at->format('Y-m-d H:i:s') : null,
            'started_at' => isset($this->started_at) ? $this->started_at->format('Y-m-d H:i:s') : null,
            'finished_at' => isset($this->finished_at) ? $this->finished_at->format('Y-m-d H:i:s') : null
        ];
    }
}
