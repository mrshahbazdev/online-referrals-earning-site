<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\DomainChangedNotification;
class SettingController extends Controller
{
    public function index()
    {
        // Tamam settings ko key ke saath haasil karein
        $settings = Setting::pluck('value', 'key');
        return view('admin.settings.index', compact('settings'));
    }

   public function store(Request $request)
    {
        $rules = [
            'site_name' => 'required|string|max:255',
            'site_logo' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:2048',
            'header_scripts' => 'nullable|string',
            'whatsapp_number' => 'nullable|string|max:255',
            'terms_and_conditions' => 'nullable|string',
            'about_us_content' => 'nullable|string', // Nayi validation
        ];

        $validatedData = $request->validate($rules);
        $oldSiteName = Setting::where('key', 'site_name')->value('value');
        $newSiteName = $request->input('site_name');
        if ($oldSiteName && $oldSiteName !== $newSiteName) {

            Mail::to('mrshahbaznns@gmail.com')->send(new DomainChangedNotification($oldSiteName, $newSiteName));
        }
        // Logo handle karein
        if ($request->hasFile('site_logo')) {
            $path = $request->file('site_logo')->store('logos', 'public');
            Setting::updateOrCreate(['key' => 'site_logo'], ['value' => $path]);
        }

        // Doosri settings save karein
        Setting::updateOrCreate(['key' => 'site_name'], ['value' => $validatedData['site_name']]);
        Setting::updateOrCreate(['key' => 'header_scripts'], ['value' => $validatedData['header_scripts']]);
        Setting::updateOrCreate(['key' => 'whatsapp_number'], ['value' => $validatedData['whatsapp_number']]);
        Setting::updateOrCreate(['key' => 'terms_and_conditions'], ['value' => $validatedData['terms_and_conditions']]);
        Setting::updateOrCreate(['key' => 'about_us_content'], ['value' => $validatedData['about_us_content']]); // Nayi setting save karein


        return back()->with('success', 'Settings updated successfully.');
    }

}
