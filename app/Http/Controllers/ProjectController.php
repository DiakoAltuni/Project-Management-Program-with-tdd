<?php

namespace App\Http\Controllers;

use App\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{

    public function index()
    {
        $projects = auth()->user()->accessibleProjects();
        //$projects = Project::all();
        return view('projects.index', compact('projects'));
    }


    public function create()
    {
        return view('projects.create');
    }


    public function store()
    {
        $project = auth()->user()->projects()->create($this->validateRequest());

        return redirect($project->path());
    }


    public function show($id)
    {
        $project = Project::with([])->findOrFail($id);
        $this->authorize('update', $project);

        return view('projects.show', compact('project'));
    }


    public function edit($id)
    {
        $project = Project::with([])->findOrFail($id);
        return view('projects.edit', compact('project'));
    }

    public function update(Project $project)
    {
        $this->authorize('update', $project);


        $project->update($this->validateRequest());


        return redirect($project->path());
    }


    public function destroy(Project $project)
    {
        $this->authorize('manage', $project);

        $project->delete();

        return redirect('/projects');
    }


    public function validateRequest()
    {

        return $this->validate(\request(),
            [
                'title' => 'required|sometimes',
                'description' => 'required|sometimes',
                'notes' => 'nullable'
            ]
        );

    }
}
