<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CMSController extends Controller
{
    public function index()
    {
        $banners = Banner::orderBy('order_priority', 'asc')->get();

        return view('admin.marketing.cms', compact('banners'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'          => 'required|string|max:255',
            'image'          => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'order_priority' => 'nullable|integer',
        ]);

        $path = $request->file('image')->store('banners', 'public');

        Banner::create([
            'title'          => $request->title,
            'subtitle'       => $request->subtitle,
            'link_url'       => $request->link_url,
            'order_priority' => $request->order_priority ?? 0,
            'image_path'     => $path,
            'is_active'      => $request->has('is_active'),
        ]);

        return redirect()->route('admin.marketing.cms.index')
            ->with('success', 'Banner uploaded successfully!');
    }


    // ===============================
    //  UPDATE
    // ===============================
    public function update(Request $request, $id)
    {
        $banner = Banner::findOrFail($id);

        $request->validate([
            'title'          => 'required|string|max:255',
            'image'          => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'order_priority' => 'nullable|integer',
        ]);

        try {

            $data = [
                'title'          => $request->title,
                'subtitle'       => $request->subtitle,
                'link_url'       => $request->link_url,
                'order_priority' => $request->order_priority ?? 0,
                'is_active'      => $request->has('is_active'),
            ];

            // jika upload gambar baru
            if ($request->hasFile('image')) {

                if ($banner->image_path &&
                    Storage::disk('public')->exists($banner->image_path)) {

                    Storage::disk('public')->delete($banner->image_path);
                }

                $data['image_path'] = $request->file('image')
                    ->store('banners', 'public');
            }

            $banner->update($data);

            return redirect()->route('admin.marketing.cms.index')
                ->with('success', 'Banner updated successfully!');

        } catch (\Exception $e) {

            return back()->with(
                'error',
                'Gagal update banner : ' . $e->getMessage()
            );
        }
    }


    public function destroy($id)
    {
        $banner = Banner::findOrFail($id);

        if ($banner->image_path &&
            Storage::disk('public')->exists($banner->image_path)) {

            Storage::disk('public')->delete($banner->image_path);
        }

        $banner->delete();

        return redirect()->route('admin.marketing.cms.index')
            ->with('success', 'Banner deleted successfully!');
    }
}
