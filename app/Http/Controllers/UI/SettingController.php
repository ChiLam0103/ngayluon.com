<?php

namespace App\Http\Controllers\UI;

use App\Http\Requests\FrontEnt\FeedbackRequest;
use App\Models\Feedback;
use App\Models\Policy;
use function dd;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingController extends Controller
{
    public function feedback(Request $request)
    {
        try {
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            $link_cv = '';
            if ($request->hasFile('link_cv')) {
                $file = $request->file('link_cv');
                $name = date("YmdHis") .'_'. $file->getClientOriginalName();
                $exection = $file->getClientOriginalExtension();
                $file->move(public_path() . '/file/link_cv/', $name);
                $link_cv = '/public/file/link_cv/' . $name;
            }
            DB::table('feedback')->insert([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'district_id' => $request->district_id,
                'type' => $request->type,
                'subject' => $request->subject,
                'content' => $request->content,
                'link_cv' => $link_cv,
                'created_at' => date("YmdHis"),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
        }
        $request->session()->flash('success', 'Gửi phản hồi thành công! cảm ơn bạn đã phản hồi đến hệ thống!');
        return redirect(url('/'));
    }

    public function policy()
    {
        $policy = Policy::where('id', '>', 0)->first();
        return view('front-ent.element.policy', ['policy' => $policy]);
    }
}
