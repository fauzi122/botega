<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Jobs\UpdateProfileJob;
use App\Models\LogsModel;
use App\Models\RequestUpdateModel;
use App\Models\UserModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Session;

class Profile extends Controller
{
    //
    public function index()
    {
        $profile = UserModel::query()->where('id',session('user')->id)->first();

        $data = [
            'title' => 'Ubah Data',
            'profile' => $profile

        ];
        return view('frontend.account.register', $data);
    }

    public function data_pribadi(){
        $profile = UserModel::query()->where('id',session('user')->id)->first();
        $riwayat = RequestUpdateModel::query()->where('user_id',session('user')->id)->orderBy('id','desc')->get();
        $data = [
            'title' => 'Ubah Data',
            'profile' => $profile,
            'riwayat'=>$riwayat

        ];
        return view('frontend.account.datapribadi', $data);
    }

    public function updateProfile(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
//    'email' => 'required|email|max:255',
            'nohp' => 'required|numeric',
//    'sub_kategori' => 'nullable|string|max:255',
            'gender' => 'nullable|in:L,P',
            'birth_at' => 'nullable|date',
            'alamat' => 'nullable|string',
            'rt' => 'nullable|numeric',
            'rw' => 'nullable|numeric',
            'foto_profile' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'npwp' => 'nullable|numeric',
            'nppk' => 'nullable|numeric',
            'nik' => 'nullable|numeric',
        ], [
            'first_name.required' => 'Kolom nama depan harus diisi.',
            'first_name.string' => 'Kolom nama depan harus berupa teks.',
            'first_name.max' => 'Kolom nama depan tidak boleh lebih dari 255 karakter.',
            'last_name.string' => 'Kolom nama belakang harus berupa teks.',
            'last_name.max' => 'Kolom nama belakang tidak boleh lebih dari 255 karakter.',
            'nohp.required' => 'Kolom nomor handphone harus diisi.',
            'nohp.numeric' => 'Kolom nomor handphone harus berupa angka.',
            'gender.in' => 'Kolom jenis kelamin harus berisi L (Laki-laki) atau P (Perempuan).',
            'birth_at.date' => 'Kolom tanggal lahir harus berupa tanggal.',
            'alamat.string' => 'Kolom alamat harus berupa teks.',
            'rt.numeric' => 'Kolom RT harus berupa angka.',
            'rw.numeric' => 'Kolom RW harus berupa angka.',
            'foto_profile.image' => 'File foto profile harus berupa gambar.',
            'foto_profile.mimes' => 'File foto profile harus memiliki format jpeg, png, jpg, atau gif.',
            'foto_profile.max' => 'Ukuran file foto profile tidak boleh lebih dari 2 MB.',
            'npwp.numeric' => 'Kolom NPWP harus berupa angka.',
            'nppk.numeric' => 'Kolom NPPK harus berupa angka.',
            'nik.numeric' => 'Kolom NIK harus berupa angka.',
        ]);


        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }


        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

//        $path_file = null;
//        if ($request->hasFile('foto_profile')) {
//            $file = $request->file('foto_profile');
//            $fileName = time() . '-' . session('user')->id . '.png';
//
//            $filePath = Storage::disk()->put('foto_profile/' . $fileName, file_get_contents($file));
//            $path_file = 'foto_profiile/' . $fileName;
//
//
//        }
        $data = [
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'hp' => $request->input('nohp'),
//            'sub_kategori' => $request->input('sub_kategori'),
            'gender' => $request->input('gender'),
            'birth_at' => $request->input('birth_at'),
            'alamat' => $request->input('alamat'),
            'rt' => $request->input('rt'),
            'rw' => $request->input('rw'),
            'npwp' => $request->input('npwp'),
            'nppk' => $request->input('nppk'),
            'nik' => $request->input('nik'),
//            'foto_path' => $path_file
        ];

        $req = [
            'user_id' => session('user')->id,
            'json_temp' => json_encode($data),
            'reason_user' => $request->input('reason_user'),
            'status' => 1,
            'created_at' => Carbon::now('Asia/Jakarta')
        ];
        $user_id = session('user')->id;
        $cek = RequestUpdateModel::query()->where('user_id', $user_id)->where('status', 'Submited')->count();

//        var_dump($cek);die();
        if ($cek > 0) {

            Session::flash('info', 'Mohon maaf perubahan anda sedang ada yang diajukan... ');
            return redirect()->back();

        } else {
            $result = RequestUpdateModel::query()->insert($req);
            $isi = [
                'description' => 'Pengajuan perubahan data member...',
                'url' => url('profile'),
            ];
            $title = 'Update Profile';
//            dispatch(new UpdateProfileJob($isi, $user_id, $title));
            $log = [
                'actions' => $title,
                'payload' => json_encode($isi),
                'user_id' => $user_id,
                'created_at' => Carbon::now('Asia/Jakarta')
            ];

            LogsModel::query()->insert($log);
            if ($result) {
                return redirect('profile')->with('success', 'Profil berhasil diperbarui.');
            } else {
                return redirect('profile')->with('error', 'Profil gagal diperbarui.');
            }
        }


    }

    public function profileimage($id)
    {
        $pi = UserModel::find($id);

//        var_dump($pi->foto_path);die();
        if ($pi == null) abort(404);
        $fn = $pi->foto_path;
        if (!Storage::exists($fn)) {
            abort(404);
        }
        $content = Storage::get($fn);
        return response($content, headers: [
            'Content-type' => 'image/png'
        ]);

    }

    public function upload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'foto' => 'required|image|mimes:jpeg,png,jpg,gif|max:5048',
        ]);
        if ($validator->fails()) {
            throw ValidationException::withMessages($validator->errors()->toArray());
        }

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $cek = UserModel::query()->where('id',session('user')->id)->first();
            if ($cek && $cek->foto_path) {
                Storage::delete($cek->foto_path);
            }
            $fileName = time() . '-' . session('user')->id . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('foto_profile', $fileName);

            if ($filePath) {
                $path_file = 'foto_profile/' . $fileName;
                UserModel::query()->where('id', session('user')->id)->update(['foto_path' => $path_file]);
                return redirect()->back()->with('success', 'Foto berhasil diunggah.');
            } else {
                return redirect()->back()->with('error', 'Gagal menyimpan foto.');
            }
        }

        return redirect()->back()->with('error', 'Gagal mengunggah foto.');
    }

}
