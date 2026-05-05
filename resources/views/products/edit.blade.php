<form action="{{ route('products.update',$product->id) }}" method="POST">
@csrf
@method('PUT')

<input type="text" name="name" value="{{ $product->name }}">
<textarea name="description">{{ $product->description }}</textarea>

<select name="created_by">
@foreach($managers as $m)
<option value="{{ $m->id }}" {{ $product->created_by == $m->id ? 'selected':'' }}>
{{ $m->name }}
</option>
@endforeach
</select>

<button type="submit">Update</button>
</form>