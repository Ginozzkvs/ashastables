<form method="POST" action="{{ route('membership-activity-limits.store') }}">
@csrf

<select name="membership_id">
@foreach($memberships as $m)
<option value="{{ $m->id }}">{{ $m->name }}</option>
@endforeach
</select>

<select name="activity_id">
@foreach($activities as $a)
<option value="{{ $a->id }}">{{ $a->name }}</option>
@endforeach
</select>

<input type="number" name="max_per_year" placeholder="Max per year">
<input type="number" name="max_per_day" placeholder="Max per day">

<button>Save</button>
</form>
