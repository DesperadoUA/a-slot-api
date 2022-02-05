<form method="post" action="/admin/task/<?= $id ?>/update">
    @csrf
    <input type="text" name="title" value="<?= $title ?>"/>
    <input type="text" name="description"  value="<?= $description ?>" />
    <button>Submit</button>
</form>
<form method="post" action="/admin/task/<?= $id ?>/destroy">
    @csrf
    <button>Delete</button>
</form>