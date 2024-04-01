$('body').on("click", ".WhishList", function(event){
    event.preventDefault();

    var This_block = $(this);
    var ProductID = $(This_block).attr('data-ProductID');

    if( $(this).hasClass("add-list") ){
        var request = BX.ajax.runComponentAction('IK:WhishList', 'AddWhishList', {
            mode: 'class',
            data: {
                'ProductID': ProductID,
            },
        }).then(function(response){
            if( response.data ){
                $(This_block).removeClass('add-list').addClass('remove-list');
                RefreshWhishListCount();
            };
        });
    }else if( $(this).hasClass("remove-list") ){
        var request = BX.ajax.runComponentAction('IK:WhishList', 'RemoveWhishList', {
            mode: 'class',
            data: {
                'ProductID': ProductID,
            },
        }).then(function(response){
            if( response.data ){
                $(This_block).removeClass('remove-list').addClass('add-list');
                RefreshWhishListCount();
            };
        });
    };
});