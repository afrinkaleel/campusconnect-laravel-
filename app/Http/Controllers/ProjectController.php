<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Project;
use App\Models\ProjectUpdate;
use App\Models\User;
use App\Models\Notification;
use App\Models\SupervisionRequest;

class ProjectController extends Controller
{
    // Student — My Projects
    public function index() {
        $projects = Project::with('supervisor')
            ->where('student_id', Auth::id())
            ->latest()->get();
        return view('projects.index', compact('projects'));
    }

    // Student — Register Project form
    public function create() {
        return view('projects.create');
    }

    // Student — Store new project
    public function store(Request $request) {
        $request->validate([
            'title'       => 'required|string|max:200',
            'description' => 'required|string',
        ]);

        Project::create([
            'title'       => $request->title,
            'description' => $request->description,
            'student_id'  => Auth::id(),
            'status'      => 'planning',
        ]);

        return redirect()->route('projects.index')
                         ->with('success', 'Project registered successfully!');
    }

    // View single project
    public function show($id) {
        $project  = Project::with(['student','supervisor',
                                   'tempSupervisor','updates'])
                            ->findOrFail($id);
        $lecturers = User::where('user_type', 'lecturer')->get();
        return view('projects.show', compact('project', 'lecturers'));
    }

    // Student — Add progress update
    public function addUpdate(Request $request, $id) {
        $request->validate([
            'update_text' => 'required|string',
            'status'      => 'required|in:planning,design,implementation,testing,completed',
        ]);

        ProjectUpdate::create([
            'project_id'  => $id,
            'update_text' => $request->update_text,
        ]);

        Project::where('project_id', $id)
               ->update(['status' => $request->status]);

        return back()->with('success', 'Progress update added!');
    }

    // HOD — All projects
    public function allProjects() {
        $projects = Project::with(['student','supervisor'])->latest()->get();
        return view('projects.all', compact('projects'));
    }

    // HOD — Assign supervisor
    public function assignSupervisor(Request $request, $id) {
        $request->validate([
            'supervisor_id' => 'required|exists:users,id',
        ]);

        $project = Project::findOrFail($id);
        $project->update(['supervisor_id' => $request->supervisor_id]);

        // Notify student
        Notification::create([
            'user_id' => $project->student_id,
            'message' => 'A supervisor has been assigned to your project: '
                         . $project->title,
        ]);

        return back()->with('success', 'Supervisor assigned successfully!');
    }

    // Lecturer — Supervised projects
    public function supervise() {
        $projects = Project::with('student')
            ->where('supervisor_id', Auth::id())
            ->latest()->get();
        return view('projects.supervise', compact('projects'));
    }

    // Lecturer — Unassigned projects
    public function unassigned() {
        $projects = Project::with('student')
            ->whereNull('supervisor_id')
            ->latest()->get();
        return view('projects.unassigned', compact('projects'));
    }

    // Lecturer — Request supervision
    public function requestSupervision(Request $request) {
        $project_id = $request->project_id;

        $exists = SupervisionRequest::where('project_id', $project_id)
            ->where('lecturer_id', Auth::id())
            ->where('status', 'pending')
            ->exists();

        if ($exists) {
            return back()->with('error', 
                'You already requested supervision for this project.');
        }

        SupervisionRequest::create([
            'project_id'  => $project_id,
            'lecturer_id' => Auth::id(),
        ]);

        // Notify HOD
        $hod = User::where('user_type', 'hod')->first();
        $project = Project::find($project_id);
        Notification::create([
            'user_id' => $hod->id,
            'message' => Auth::user()->name 
                         . ' requested to supervise: ' . $project->title,
        ]);

        return back()->with('success', 
            'Supervision request sent to HOD!');
    }

    // HOD — View supervision requests
    public function supervisionRequests() {
        $requests = SupervisionRequest::with(['project.student','lecturer'])
            ->orderByRaw("CASE status WHEN 'pending' THEN 1 ELSE 2 END")
            ->latest()->get();
        return view('projects.supervision_requests', compact('requests'));
    }

    // HOD — Approve/Reject supervision request
    public function handleSupervisionRequest(Request $request, $id) {
        $sr      = SupervisionRequest::findOrFail($id);
        $action  = $request->action;
        $project = Project::find($sr->project_id);

        if ($action === 'approve') {
            $project->update(['supervisor_id' => $sr->lecturer_id]);
            $sr->update(['status' => 'approved']);

            // Reject others
            SupervisionRequest::where('project_id', $sr->project_id)
                ->where('request_id', '!=', $id)
                ->where('status', 'pending')
                ->update(['status' => 'rejected']);

            // Notify lecturer
            Notification::create([
                'user_id' => $sr->lecturer_id,
                'message' => 'Your supervision request for "'
                             . $project->title . '" has been approved!',
            ]);

            // Notify student
            Notification::create([
                'user_id' => $project->student_id,
                'message' => $sr->lecturer->name
                             . ' has been assigned as your supervisor for "'
                             . $project->title . '".',
            ]);
        } else {
            $sr->update(['status' => 'rejected']);
            Notification::create([
                'user_id' => $sr->lecturer_id,
                'message' => 'Your supervision request for "'
                             . $project->title . '" was not approved.',
            ]);
        }

        return back()->with('success', 'Request ' . $action . 'd successfully!');
    }
}