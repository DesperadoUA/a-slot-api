@if (count($tasks) === 1)
    I have one record!
@else
    I don't have any records!
@endif
<br>
<ul>
@foreach ($tasks as $task)
        <li><a href="/admin/task/{{$task->id}}"> {{ $task->title }}</a></li>
@endforeach
</ul>