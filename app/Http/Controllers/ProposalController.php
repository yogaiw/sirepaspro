<?php

namespace App\Http\Controllers;

use App\Models\Proposal;
use App\Models\Revision;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProposalController extends Controller
{
    public function create(Request $request) {

        $this->validate($request, [
            'title' => 'required',
            'abstract_indonesian' => 'required',
            'abstract_english' => 'required',
            'proposal' => 'required|mimes:pdf,doc,docx,txt'
        ]);

        $proposal = Proposal::create([
            'author_id' => Auth::user()->id,
            'title' => $request->title,
            'abstract_indonesian' => $request->abstract_indonesian,
            'abstract_english' => $request->abstract_english,
            'status' => 0
        ]);

        $filename = time().'-'.$request->file('proposal')->getClientOriginalName();
        $request->file('proposal')->move('proposals', $filename);

        $revision = new Revision();
        $revision->proposal_id = $proposal->id;
        $revision->from_id = Auth::user()->id;
        $revision->message = "Inisiasi Proposal";
        $revision->file = $filename;
        $revision->save();

        return redirect()->route('dashboard.student');
    }

    public function detail($id) {
        $proposal = Proposal::find($id);
        $revisions = Revision::where('proposal_id', $id)->orderBy('id', 'desc')->get();
        return view('lecturer.detail', compact('proposal', 'revisions'));
    }

    public function submitRevision(Request $request, $proposal_id) {
        $this->validate($request, [
            'message' => 'required',
            // 'file' => 'required'
        ]);

        Revision::create([
            'proposal_id' => $proposal_id,
            'from_id' => Auth::user()->id,
            'message' => $request->message,
            // 'file' => $request->file
        ]);

        return back();
    }
}
