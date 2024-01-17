<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FileRequest extends FormRequest
{
    const MIME_VIDEO = 'video/mp4,video/3gpp,video/ogg,video/MP2T,video/x-msvideo,video/x-ms-wmv,video/mpeg';
    const MIME_IMAGE = 'image/jpg,image/jpeg,image/bmp,image/x-windows-bmp,image/png,image/gif,image/x-icon';

    public static $mimeTypesMap = [
        'video' => self::MIME_VIDEO,
        'video.thumbnail' => self::MIME_IMAGE,
        'user.image' => self::MIME_IMAGE,
        'producer.image' => self::MIME_IMAGE
    ];

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array[]
     */
    public function rules()
    {
        $category = $this->post('category');
        $fileRules = ['required', 'file'];
        if($category && key_exists($category, static::$mimeTypesMap)) {
            $fileRules[] = 'mimetypes:' . static::$mimeTypesMap[$category];
        }
        return [
            'category' => ['required', 'string', 'max:30', Rule::in(array_keys(static::$mimeTypesMap))],
            'UploadFiles' => $fileRules,
        ];
    }
}
