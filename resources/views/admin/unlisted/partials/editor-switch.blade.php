{{-- Active/inactive switch. The hidden input sends "0" when the box is unticked. --}}
<label class="se-switch" title="{{ $title ?? 'Show or hide this on the public site' }}">
    <input type="hidden" name="{{ $name }}" value="0">
    <input type="checkbox" name="{{ $name }}" value="1" @checked($checked)>
    <span class="se-switch-track"></span>
    <span class="se-switch-label"><span class="se-on">{{ $on ?? 'Active' }}</span><span class="se-off">{{ $off ?? 'Inactive' }}</span></span>
</label>
