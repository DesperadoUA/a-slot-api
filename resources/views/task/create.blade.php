<form method="post" action="/admin/task/store">
    @csrf
    <input type="text" name="title" />
    <input type="text" name="description" />
    <button>Submit</button>
</form>