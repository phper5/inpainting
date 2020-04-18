<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    public $incrementing = false;
    const MAX_WIDTH = 720;

    public  function toResponse()
    {
        $auto_orient = true;
        $usedCdn = false;
        $data = [
            'resource_id'=>$this->id,
            'type'=>$this->type,
            'filename'=>$this->filename,
            'size'=>$this->size,
            'bucket'=>$this->bucket,
            'object'=>$this->object,
            'created_at'=>$this->created_at->timestamp,
            'url' => $this->getUrl(null,null,$auto_orient,false)
        ];
        if ($this->type == 'image')
        {
            $json = json_decode($this->attrs,true);
            $data['image_height'] = $json['image_height']??0;
            $data['image_width'] = $json['image_width']??0;
            $data['canvas_width'] = $data['image_width'];
            $data['canvas_height'] = $data['image_height'];
            if ($data['image_width']>self::MAX_WIDTH) {
                $data['canvas_width'] = self::MAX_WIDTH;
                $data['canvas_height'] = intval($data['image_height']*$data['canvas_width']/$data['image_width']);
            }
            $data['image_canvas_url'] = $this->getUrl($data['canvas_width'],3600,$auto_orient,false);
            $data['image_original_url'] = $this->getUrl(null,3600,$auto_orient,$usedCdn);

        }
        return $data;

    }
    public function getUrl($width=null,$time=null,$auto_orient=1,$usedCdn=true)
    {
        if ($this->type =='image')
        {
            $url = (new Oss())->getSignedObjectUrl($this->bucket,$this->object,$width,$time,$usedCdn,$auto_orient);
        }elseif ($this->type =='audio')
        {
            $url = (new Oss())->getSignedObjectUrl($this->bucket,$this->object,null,$time,$usedCdn,$auto_orient);
        }
        elseif ($this->type == 'video') {
            $url =  (new Oss())->getSignedObjectUrl($this->bucket,$this->object,null,$time,$usedCdn,$auto_orient);
        }
        else{
            $url = (new Oss())->getSignedObjectUrl($this->bucket,$this->object,null,$time,$usedCdn,$auto_orient);
        }
        return $url;
    }
}
