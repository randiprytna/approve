<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Complaint;
use App\Models\ComplaintImage;

class ComplaintController extends Controller
{
    public function index()
    {
        return view('pages.user.complaint');
    }

    public function data()
    {
        $complaints = Complaint::where('user_id', auth()->user()->id)->get();

        $datas = [];        
        foreach ($complaints as $complaint) {
            $datas[] = [
                'id' => $complaint->id,
                'complaint' => $complaint->complaint,
                'status' => $complaint->status,
                'complaint_created' => $complaint->created_at->format('d F Y H.i')
            ];
        }

        return \DataTables::of($datas)->toJson();
    }

    public function getImages($complaintId)
    {
        $complaint = Complaint::with('complaintImages')->find($complaintId);
        $images = $complaint->complaintImages->pluck('image_url');

        return response()->json($images);
    }

    public function add()
    {
        return view('pages.user.complaint-add');
    }

    public function addaction(Request $request)
    {
        $request->validate([
            'complaint' => 'required'
        ]);

        $complaint = new Complaint;
        $complaint->user_id = auth()->user()->id;
        $complaint->complaint = $request->complaint;
        $complaint->status = 'waiting_for_approval';
        $complaint->save();

        if ($request->images !== null && is_array($request->images)) {
            foreach ($request->images as $string) {
                $imageData = json_decode($string, true);
                if ($imageData !== null && is_array($imageData) && isset($imageData[0]['image_path']) && isset($imageData[0]['image_url'])) {
                    $image = new ComplaintImage;
                    $image->complaint_id = $complaint->id;
                    $image->image_path = $imageData[0]['image_path'];
                    $image->image_url = $imageData[0]['image_url'];
                    $image->save();
                }
            }
        }

        return redirect()->route('user.complaint')->with('success', 'Successfully submitted the report, the admin will check your report first, wait a moment, for status updates please refresh the page');
    }

    public function uploadImages(Request $request)
    {
        $responses = [];

        foreach ($request->file('images') as $image) {
            $allowedTypes = ['jpg', 'jpeg', 'png', 'webp'];
            $extension = $image->getClientOriginalExtension();
            if (!in_array($extension, $allowedTypes)) {
                return response()->json(['error' => 'Invalid file type. Only jpg, jpeg, png, and webp are allowed.'], 400);
            }
            $fileSize = $image->getSize();
            if ($fileSize > 2097152) {
                return response()->json(['error' => 'File size exceeds the maximum limit of 2 MB.'], 400);
            }
            $path = $image->store('images/items');
            $url = Storage::url($path);

            $responses[] = [
                'image_path' => $path,
                'image_url' => $url,
            ];
        }

        return response()->json($responses);
    }

    public function getHistory($complaintId)
    {
        $complaint = Complaint::find($complaintId);
        
        $formattedHistory = [
            'created_at' => date('d F Y H:i', strtotime($complaint->created_at)),
            'approved_at' => $complaint->approved_at ? date('d F Y H:i', strtotime($complaint->approved_at)) : null,
            'resolved_at' => $complaint->resolved_at ? date('d F Y H:i', strtotime($complaint->resolved_at)) : null,
        ];

        return response()->json($formattedHistory);
    }
}
