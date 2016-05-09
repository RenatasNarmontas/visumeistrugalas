$('input:checkbox').change(function (e) {
   e.preventDefault();

    // Determine checkbox
    var name = $(this).attr('name');
    var id = $(this).attr('id');
    var isChecked = $(this).is(':checked') ? 1 : 0;
    var data = [];
    data[0] = name;
    data[1] = id;
    data[2] = isChecked;

    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: '/admin/users',
        data: { data:data },
        error: function (value, dataType) {
            alert(value.message);
        }
    });
});

function delete_city(name, cityId, thisObj) {
    if (confirm("Do you want to delete '" + name + "'?")) {
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: '/admin/delete_city',
            data: {id:cityId},
            error: function (value, dataType) {
                alert(value.message);
            },
            success: function (value) {
                $(thisObj).parents("tr:first").remove();
            }
        });
    }
}

function delete_user(username, userId, thisObj) {
    if (confirm("Do you want to delete '" + username + "'?")) {
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: '/admin/delete_user',
            data: {id:userId},
            error: function (value, dataType) {
                alert(value.message);
            },
            success: function (value) {
                $(thisObj).parents("tr:first").remove();
            }
        });
    }
}

function  delete_api(api, userId, thisObj) {
    if (confirm("Do you want to delete '" + api + "'?")) {
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: '/admin/delete_api',
            data: {id:userId},
            error: function (value, dataType) {
                alert(value.message);
            },
            success: function (value) {
                // TODO: refresh table
                //$(thisObj).parents("tr:first").remove();
            }
        });
    }
}