<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;


class Task extends Model
{
    use RecordsActivity;
     public $old = [];
    protected $guarded = [];
    protected $touches = ['project'];
    protected $casts = [
        'completed' => 'boolean'
    ];

    protected static $recordableEvents = ['created','deleted'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Mark the task as complete.
     */
    public function complete()
    {
        $this->update(['completed' => true]);
        $this->recordActivity('completed_task');
    }

    /**
     * Mark the task as complete.
     */
    public function incomplete()
    {
        $this->update(['completed' => false]);
        $this->recordActivity('incompleted_task');

    }

    public function path()
    {
        return "/projects/{$this->project->id}/tasks/{$this->id}";
    }

//    /**
//     * Record activity for a project.
//     *
//     * @param string $description
//     */
//    public function recordActivity($description)
//    {
//
//        $this->activity()->create([
//            'user_id' => ($this->project ?? $this)->owner->id,
//            'project_id' => $this->project_id,
//            'description' => $description
//        ]);
//
//    }

    /**
     * The activity feed for the project.
     *
     * @return MorphMany
     */
    public function activity()
    {

        return $this->morphMany(Activity::class, 'subject')->latest();

    }
}
