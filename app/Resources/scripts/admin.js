function enable_user_action(userId, type, cb) {
    var data = [];
    data[0] = type;
    data[1] = userId;
    data[2] = cb.checked;

    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: '/admin/users',
        data: { data:data },
        error: function (value, dataType) {
            alert(value.message);
        },
    });
}

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

function delete_api(api, userId, thisObj) {
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

function chart_data() {
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: '/graph_get_data',
        success: function (result) {
                var jsonData = JSON.parse(result)
                alert(result.message);
                // do something with data
        },
        error: function (result) {
            alert(result.message);
        }
    })
}
