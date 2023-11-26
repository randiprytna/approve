<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Complaint;
use Carbon\Carbon;

class HandleComplaintController extends Controller
{
    public function index()
    {
        return view('pages.admin.complaint');
    }

    public function data()
    {
        $complaints = Complaint::with('user')->get();

        $datas = [];        
        foreach ($complaints as $complaint) {
            $datas[] = [
                'id' => $complaint->id,
                'name' => $complaint->user->name,
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

    public function updateStatus(Request $request)
    {
        $complaint = Complaint::findOrFail($request->complaintId);
        $complaint->status = $request->newStatus;
        if($request->newStatus === 'approved'){
            $complaint->approved_at = now();
        } else if ($request->newStatus === 'complaint_resolved'){
            $complaint->resolved_at = now();
        }
        $complaint->save();

        return response()->json($complaint);
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
