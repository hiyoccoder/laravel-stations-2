<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;

class UpdateScheduleRequest extends FormRequest
{
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
     * @return array
     */
    public function rules()
    {
        return [
            'movie_id' => ['required', 'exists:movies,id'],
            'screen_id' => ['required', 'exists:screens,id'],
            'start_time_date' => ['required', 'date_format:Y-m-d', 'before_or_equal:end_time_date'],
            'start_time_time' => ['required', 'date_format:H:i'],
            'end_time_date' => ['required', 'date_format:Y-m-d', 'after_or_equal:start_time_date'],
            'end_time_time' => ['required', 'date_format:H:i'],
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // 基本的なバリデーションが通った場合のみ時刻の詳細チェックを行う
            if (!$validator->errors()->hasAny(['start_time_date', 'start_time_time', 'end_time_date', 'end_time_time'])) {
                $this->validateTimeLogic($validator);
            }
        });
    }

    /**
     * 時刻の論理的なバリデーション
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    protected function validateTimeLogic($validator)
    {
        try {
            $startDateTime = Carbon::createFromFormat(
                'Y-m-d H:i',
                $this->start_time_date . ' ' . $this->start_time_time,
                'Asia/Tokyo'
            );

            $endDateTime = Carbon::createFromFormat(
                'Y-m-d H:i',
                $this->end_time_date . ' ' . $this->end_time_time,
                'Asia/Tokyo'
            );

            // 1. 開始時刻が終了時刻より後でないかチェック（同じ時刻も含む）
            if ($startDateTime->greaterThanOrEqualTo($endDateTime)) {
                $validator->errors()->add('start_time_time', '開始時刻は終了時刻より前に設定してください。');
                $validator->errors()->add('end_time_time', '終了時刻は開始時刻より後に設定してください。');
                return;
            }

            // 2. 上映時間が5分より長いかチェック
            $durationInMinutes = $startDateTime->diffInMinutes($endDateTime);
            if ($durationInMinutes <= 5) {
                $validator->errors()->add('start_time_time', '開始時刻と終了時刻の差は5分以上にしてください。');
                $validator->errors()->add('end_time_time', '開始時刻と終了時刻の差は5分以上にしてください。');
                return;
            }
        } catch (\Exception $e) {
            $validator->errors()->add('start_time_time', '日時の形式に問題があります。');
        }
    }

    public function messages()
    {
        return [
            'movie_id.required' => '映画の選択は必須です。',
            'movie_id.exists' => '選択された映画が存在しません。',
            'start_time_date.required' => '開始日付は必須です。',
            'start_time_date.date_format' => '開始日付の形式が正しくありません。',
            'start_time_date.before_or_equal' => '開始日付は終了日付より前または同じ日にしてください。',
            'start_time_time.required' => '開始時刻は必須です。',
            'start_time_time.date_format' => '開始時刻の形式が正しくありません。',
            'end_time_date.required' => '終了日付は必須です。',
            'end_time_date.date_format' => '終了日付の形式が正しくありません。',
            'end_time_date.after_or_equal' => '終了日付は開始日付より後または同じ日にしてください。',
            'end_time_time.required' => '終了時刻は必須です。',
            'end_time_time.date_format' => '終了時刻の形式が正しくありません。',
        ];
    }
}
