<?php


namespace App\Modules\Passport\src\Resources;


use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class AgreementResource extends JsonResource
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
            'id'            =>  isset($this->id) ? (int) $this->id : null,
            'title'         =>  isset($this->name) ? (string) $this->name : null,
            'agreement'     =>  isset($this->agreement) ? (string) $this->agreement : null,
            'company_id'    =>  isset($this->company_id) ? (int) $this->company_id : null,
            'entity'        =>  isset($this->company->name) ? (string) $this->company->name : null,
            'summary'       =>  $this->getSummary(),
            'description'   =>  isset($this->description) ? json_decode($this->description, true) : null,
            'comments_count'=>  isset($this->comments_count) ? (int) $this->comments_count : 0,
            'rating'        =>  isset($this->rate) ? (float) number_format($this->rate, 1) : 0,
            'raters'        =>  isset($this->raters) ? (int) $this->raters : 0,
            'images'        =>  ImageResource::collection( $this->whenLoaded('images') ),
            'comments'      =>  CommentsResource::collection( $this->whenLoaded('comments') ),
            'created_at'    =>  isset($this->created_at) ? $this->created_at->format('Y-m-d H:i:s') : null,
            'updated_at'    =>  isset($this->updated_at) ? $this->updated_at->format('Y-m-d H:i:s') : null,
        ];
    }

    public function getSummary()
    {
        $data = isset($this->description) ? json_decode($this->description, true) : [];
        $data = isset($data['content']) ? collect($data['content']) : collect([]);
        $text = $data->where('type', 'paragraph')->first();
        $text = is_null($text) ? [] : $text;
        $text = isset( $text['content'][0]['text'] ) ? (string) $text['content'][0]['text'] : null;
        return is_string($text) && strlen($text) > 100
            ? Str::substr($text, 0, 100)."..."
            : $text;
    }
}
