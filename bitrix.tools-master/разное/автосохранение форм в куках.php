 <script>

   function isObject(value) {
      return (
        typeof value === 'object' &&
        value !== null &&
        !Array.isArray(value)
        );
  }

  $('body').on('change input','textarea,input[type="text"],input[type="email"],input[type="hidden"]',function(){
    $.cookie($(this).attr('name'),$(this).val());  
});
  setTimeout(function(){
    $('textarea,input[type="text"],input[type="email"],input[type="hidden"]').each(function(){
        if($.cookie($(this).attr('name'))&&!isObject($.cookie($(this).attr('name'))))
            {$(this).val($.cookie($(this).attr('name')));}
    })
},1000);
</script>            