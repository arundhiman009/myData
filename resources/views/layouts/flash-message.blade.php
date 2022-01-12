@if (Session::get('success'))
<script>
$(function(){
    toastr.success('{{Session::get('success')}}');
});
</script>
@endif
@if (Session::get('error'))
<script>
$(function(){
    toastr.error('{{Session::get('error')}}');
});
</script>
@endif