<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdvertiserUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->user()->isAdvertiser();
    }

    public function getModelAttributes()
    {
        $attributes = [];
        if($this->post('name'))
            $attributes['name'] = $this->post('name');
        if($this->newPassword()) {
            $attributes['password'] = $this->post('password');
            $attributes['password_new'] = $this->post('password_new');
        }
        if($this->post('advertiser_type'))
            $attributes['advertiser_type'] = $this->post('advertiser_type');
        return $attributes;
    }


    public function newPassword()
    {
        return !empty($this->post('password_new'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $newPassword = $this->newPassword();
        return [
            'name' => ['string'],
            'password' => [Rule::requiredIf($newPassword), 'string'],
            'password_new' => [Rule::requiredIf($newPassword), 'string', 'min:8'],
            'password_new_confirmation' => [Rule::requiredIf($newPassword), 'string', 'min:8', 'same:password_new'],
            'advertiser_type' => ['string', Rule::in(['individual', 'business', 'agency', 'brand'])],
        ];
    }
}
