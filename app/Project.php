<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

/**
 * @method static create(array|\Illuminate\Http\Request|string $request)
 */
class Project extends Model
{
    use RecordsActivity;

    protected $guarded = [];

    public $old = [];


    public function path()
    {
        return '/projects/' . $this->id;
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class, 'project_id');
    }

    public function addTask($body)
    {
        return $this->tasks()->create(compact('body'));

    }

    public function addTasks($tasks)
    {
        return $this->tasks()->createMany($tasks);
    }

    public function invite(User $user)
    {
        $this->members()->attach($user);
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'project_members')->withTimestamps();
    }

        public function activity()
        {
            return $this->hasMany(Activity::class)->latest();
        }

}
