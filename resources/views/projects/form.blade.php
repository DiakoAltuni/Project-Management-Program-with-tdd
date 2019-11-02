@csrf
<div class="field mb-6">
    <div class="control">
        <input type="text"
               name="title"
               class="input bg-transparent border border-muted-dark rounded p-2 text-xs w-full"
               placeholder="Title"
               required
               value="{{$project->title}}">
    </div>
</div>

<div class="field">
    <div class="controller">
                <textarea name="description" rows="5"
                          class="input bg-transparent border border-muted-dark rounded p-2 text-xs w-full"
                          placeholder="description">{{$project->description}}</textarea>
    </div>
</div>
<br>
<div class="field">
    <div class="controller">
        <button type="submit" class="button is-link mr-2">{{$buttonText}}</button>

        <a href="{{ $project->path() }}" class="text-default">Cancel</a>
        @include('errors')
    </div>
</div>



