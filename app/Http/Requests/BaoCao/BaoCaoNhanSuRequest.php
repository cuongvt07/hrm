<?php

namespace App\Http\Requests\BaoCao;

use Illuminate\Foundation\Http\FormRequest;

class BaoCaoNhanSuRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'tu_ngay' => 'nullable|date',
            'den_ngay' => 'nullable|date|after_or_equal:tu_ngay',
            'phong_ban_id' => 'nullable|exists:phong_ban,id',
            'chuc_vu_id' => 'nullable|exists:chuc_vu,id'
        ];
    }

    public function messages()
    {
        return [
            'den_ngay.after_or_equal' => 'Đến ngày phải sau hoặc bằng Từ ngày',
            'phong_ban_id.exists' => 'Phòng ban không tồn tại',
            'chuc_vu_id.exists' => 'Chức vụ không tồn tại'
        ];
    }
}