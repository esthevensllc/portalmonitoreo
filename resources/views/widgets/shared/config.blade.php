@includeWhen(!empty($widget['wrapper']), 'backpack::widgets.inc.wrapper_start')
<script>
    const config = @json($widget['config']);
</script>
@includeWhen(!empty($widget['wrapper']), 'backpack::widgets.inc.wrapper_end')