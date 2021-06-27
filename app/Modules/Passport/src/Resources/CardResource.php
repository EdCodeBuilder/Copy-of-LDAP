<?php


namespace App\Modules\Passport\src\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class CardResource extends JsonResource
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
            'title' => isset($this->title) ? (string) $this->title : null,
            'description' => isset($this->description) ? (string) $this->description : null,
            'btn_text' => isset($this->btn_text) ? (string) $this->btn_text : null,
            'flex'  => isset($this->btn_text) ? (string) $this->btn_text : null,
            'src'   => $this->when(isset($this->src), $this->src),
            'lottie' =>   $this->when(
                isset($this->lottie),
                json_decode(
                    file_get_contents(
                        storage_path("app/public/lottie/$this->lottie")
                    )
                )
            ),
            'to'    => $this->when(isset($this->to), [ 'name' => $this->to ]),
            'href'  => $this->when(isset($this->href), $this->href),
        ];
    }
}
