

$('.favorite table').on('click', '.btn-order', function() {
        let action = 'getCheckFavorite';
        let comm = [];
        let id = $(this).closest('tr').data('id');
        comm.push({
            name: 'ID[]',
            value: id
        });
        comm.push({
            name: 'action',
            value: action
        });
        comm.push({
            name: 'LID',
            value: 'f1'
        });
        comm.push({
            name: 'OUT_LID',
            value: 's1'
        });
        $.post('/local/components/forumedia/basket/ajax.php', comm).done(function(ret) {
             if(ret['RESULT']){
                window.location.href=/order/;}
            if(ret['MESSAGE'])
                windowMess(ret['MESSAGE'],false); 
        });
    })

  $('.favorite table').on('click', '.btn-del', function() {
        let action = 'removeFromBasket';
        let id = $(this).closest('tr').data('id');
        let tr = $(this).closest('tr');
        let comm = [];
        comm.push({
            name: 'ID',
            value: id
        });
        comm.push({
            name: 'action',
            value: action
        });
        comm.push({
            name: 'LID',
            value: 'f1'
        });
        comm.push({
            name: 'OUT_LID',
            value: 's1'
        });
        $.post('/local/components/forumedia/basket/ajax.php', comm).done(function(ret) {
            if ($.isEmptyObject(ret.ITEMS[id])) {
                tr.remove();
            }
        });
    })

   $('.favorite table').on('click', '.btn-bask', function() {
        setTimeout(function(){location.reload();},500); 
    })