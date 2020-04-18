<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    public $incrementing = false;

    const STATUS_INIT = 0;
    const STATUS_QUEUE = 10;
    const STATUS_PROCESS = 20;
    const STATUS_FINISH = 30;
    const STATUS_ERROR = -10;

    public function toResponse($config=[])
    {
        $data = [
            'status'=>$this->status,
            'progress'=>$this->progress,
            'task_id' => $this->id,
            'target_file' => [],
            'source_file' => [],
        ];
        if ($this->target_file)
        {
            $target = json_decode($this->target_file,true);
            if ($target && $resource = Resource::find($target[0]))
            {
                $data['target_file'] = [$resource->toResponse()];
            }
            else{
                $data['target_file2'] = 'not found';
            }
        }
        if ((isset($config['sourceDetail']) && $config['sourceDetail']) || $this->target_file) {
            if ($resource = Resource::find($this->source_file))
            {
                $data['source_file'] = [$resource->toResponse()];
            }
        }
        if ((isset($config['args']) && $config['args'])) {
            $data['args'] = json_decode($this->args);
        }
        return $data;
    }
}
